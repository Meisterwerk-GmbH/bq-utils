<?php

namespace Meisterwerk\BqUtils;

class BqRestManager
{
    private string $apiKey;

    private string $url;

    public function __construct(string $apiKey, string $url)
    {
        $this->apiKey = $apiKey;
        $this->url = $url;
    }

    /**
     * @throws BqRequestException
     */
    public function get(string $endpoint)
    {
        return BqUtil::request([
            CURLOPT_URL => $this->url .  $endpoint,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer '.$this->apiKey
            ],
        ]);
    }

    /**
     * @throws BqRequestException
     */
    public function put(string $endpoint, $postFields)
    {
        return BqUtil::request([
            CURLOPT_URL => $this->url . $endpoint,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => json_encode($postFields),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer '.$this->apiKey,
                'Accept: application/json',
                'Content-Type: application/json',
            ],
        ]);
    }

    /**
     * @throws BqRequestException
     */
    public function post(string $endpoint, $postFields)
    {
        return  BqUtil::request([
            CURLOPT_URL => $this->url . $endpoint,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($postFields),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer '.$this->apiKey,
                'Accept: application/json',
                'Content-Type: application/json',
            ],
        ]);
    }
}