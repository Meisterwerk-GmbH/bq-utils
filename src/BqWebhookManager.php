<?php

namespace Meisterwerk\BqUtils;

class BqWebhookManager
{
    private const BQ_WEBHOOK_ENDPOINT_V4 = '/webhook_endpoints';

    public function __construct(
        private string $webhookPath,
        private string $webhookSecret,
        private string $webhookFilePath,
        private BqRestManager $restManagerV4
    ) {
    }

    /**
     * @deprecated see BqWebhookManager->registerV4, this function was renamed
     */
    public function register($event): void
    {
        $this->registerV4($event);
    }

    public function registerV4($event): void
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
            $response = $this->restManagerV4->post(self::BQ_WEBHOOK_ENDPOINT_V4, $postfields);
            $data = file_exists($this->webhookFilePath)
                ?  json_decode(file_get_contents($this->webhookFilePath), true)
                : [];
            $data[$event] = $response->data->id;
            file_put_contents($this->webhookFilePath, json_encode($data, JSON_PRETTY_PRINT));
        } catch (BqRequestException $e) {
            var_dump('Request error: ' . $e->getMessage() . PHP_EOL . 'Webhook was not created.');
        }
    }

    /**
     * @deprecated see BqWebhookManager->unregisterV4, this function was renamed
     */
    public function unregister($event): void
    {
       $this->unregisterV4($event);
    }

    public function unregisterV4($event): void
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
            $this->restManagerV4->delete(self::BQ_WEBHOOK_ENDPOINT_V4 . '/' . $webhookId);
            unset($data[$event]);
            file_put_contents($this->webhookFilePath, json_encode($data, JSON_PRETTY_PRINT));
        } catch (BqRequestException $e) {
            var_dump('Request error: ' . $e->getMessage() . PHP_EOL . 'Webhook has not been deleted.');
        }
    }
}