<?php

namespace Meisterwerk\BqUtils;

class WebhookManager
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
        $targetUrl = 'https://'.$this->webhookPath.'?secret='.urlencode($this->webhookSecret).'&dummy2-param='.urlencode($event);
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
            var_dump('Fehler bei Request: ' . $e->getMessage() . PHP_EOL . 'Webhook wurde nicht erstellt.');
        }
    }

    public function unregister($event): void
    {
        if(!file_exists($this->webhookFilePath)) {
            var_dump('Fehler: Keine Datei unter dem Pfad auffindbar!');
            throw new \Exception();
        }
        $data = json_decode(file_get_contents($this->webhookFilePath), true);
        if(!array_key_exists($event, $data)) {
            var_dump('Fehler: Angegebene Datei enthält keine Webhook-ID zu diesem Event!');
            throw new \Exception();
        }
        $webhookId = $data[$event];
        try {
            $this->restManagerV4->delete(self::BQ_WEBHOOK_ENDPOINT . '/' . $webhookId);
            unset($data[$event]);
            file_put_contents($this->webhookFilePath, json_encode($data, JSON_PRETTY_PRINT));
        } catch (BqRequestException $e) {
            var_dump('Fehler bei Request: ' . $e->getMessage() . PHP_EOL . 'Webhook wurde nicht gelöscht.');
        }
    }
}