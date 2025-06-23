<?php

namespace Meisterwerk\BqUtils;

class BqWebhookManager
{
    private const BQ_WEBHOOK_ENDPOINT = '/webhook_endpoints';

    private string $webhookPath;

    private string $webhookSecret;

    private string $webhookFilePath;

    private BqRestManager $restManagerV4;

    public function __construct(string $bqApiKey, string $webhookPath, string $webhookSecret, string $webhookFilePath)
    {
        $this->webhookPath = $webhookPath;
        $this->webhookSecret = $webhookSecret;
        $this->webhookFilePath = $webhookFilePath;
        $this->restManagerV4 = new BqRestManager($bqApiKey, 'https://rentshop.booqable.com/api/4');
    }

    public function register($event): void
    {
        $encodedSecret = urlencode($this->webhookSecret);
        $encodedEvent = urlencode($event);
        $targetUrl = "https://{$this->webhookPath}?secret={$encodedSecret}&dummy-param={$encodedEvent}";
        $postfields = [
            'data' => [
                'type' => 'webhook_endpoints',
                'attributes' => [
                    'url'     => $targetUrl,
                    'version' => 1,
                    'events'  => [
                        $event
                    ],
                ],
            ],
        ];
        try {
            $response = $this->restManagerV4->post(self::BQ_WEBHOOK_ENDPOINT, $postfields);
            $data = file_exists($this->webhookFilePath)
                ?  json_decode(file_get_contents($this->webhookFilePath), true)
                : [];
            $data[$event] = $response->data->id;
            file_put_contents($this->webhookFilePath, json_encode($data, JSON_PRETTY_PRINT));
        } catch (BqRequestException $e) {
            var_dump('Request error: ' . $e->getMessage() . PHP_EOL . 'Webhook was not created.');
        }
    }

    public function unregister($event): void
    {
        if(!file_exists($this->webhookFilePath)) {
            var_dump('Error: No file can be found under the path!');
            throw new \Exception();
        }
        $data = json_decode(file_get_contents($this->webhookFilePath), true);
        if(!array_key_exists($event, $data)) {
            var_dump('Error: Specified file does not contain a webhook ID for this event!');
            throw new \Exception();
        }
        $webhookId = $data[$event];
        try {
            $this->restManagerV4->delete(self::BQ_WEBHOOK_ENDPOINT . '/' . $webhookId);
            unset($data[$event]);
            file_put_contents($this->webhookFilePath, json_encode($data, JSON_PRETTY_PRINT));
        } catch (BqRequestException $e) {
            var_dump('Request error: ' . $e->getMessage() . PHP_EOL . 'Webhook has not been deleted.');
        }
    }
}