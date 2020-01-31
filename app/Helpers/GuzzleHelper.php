<?php

namespace App\Helpers;

class GuzzleHelper
{
    public  function post($payload, $url)
	{
		try {
			$client = new \GuzzleHttp\Client();

			$header = [
				'Content-Type' => 'application/json',
				'Accept' => 'application/json',
				];
				
			$response = $client->post(
				$url,
				[
					'headers' => $header,
					'body'    => json_encode($payload)
				]);

			$this->setHeader($response->getHeaders());
            return json_decode($response->getBody(), true);
        }
        catch (\GuzzleHttp\Exception\ConnectException $e) {
            return json_decode($e->getResponse()->getBody()->getContents(), true);
        } 
        catch (\GuzzleHttp\Exception\ClientException $e) {
			return json_decode($e->getResponse()->getBody()->getContents(), true);
		}
	}
}