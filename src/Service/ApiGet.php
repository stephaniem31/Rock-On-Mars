<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class ApiGet
{

    public function getPhotoApi(string $url)
    {
        $client = HttpClient::create();

        $response = $client->request('GET', $url);

        return $response->toArray();
    }
}
