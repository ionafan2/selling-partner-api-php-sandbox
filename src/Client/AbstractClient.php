<?php

declare(strict_types=1);

namespace SandboxApp\Client;

use GuzzleHttp\Psr7\Request;
use SandboxApp\ApiClientConfiguration;
use SandboxApp\ApiTransportInterface;
use SandboxApp\RequestSignature;

abstract class AbstractClient
{
    protected const DEFAULT_HTTP_VERSION = '1.1';

    protected ApiTransportInterface $apiTransport;
    protected RequestSignature $requestSignature;
    protected ApiClientConfiguration $config;

    public function __construct(
        ApiTransportInterface $apiTransport,
        RequestSignature $requestSignature,
        ApiClientConfiguration $config
    )
    {
        $this->apiTransport = $apiTransport;
        $this->requestSignature = $requestSignature;
        $this->config = $config;
    }

    protected function send(
        $method,
        $resourcePath,
        $queryParams = null,
        $headers = [],
        $body = null,
        $version = self::DEFAULT_HTTP_VERSION,
        array $options = []
    ): ?array
    {
        $uri = $this->config->getHost() . $resourcePath;

        if (!empty($queryParams)) {
            $uri = ($uri . '?' . http_build_query($queryParams));
        }

        $response = $this->apiTransport->send(
            $this->requestSignature->signRequest(
                new Request(
                    $method,
                    $uri,
                    $headers,
                    $body,
                    $version
                )
            ),
            $options
        )->getBody()->getContents();

        $response = json_decode($response, true);

        if (0 !== json_last_error()) {
            var_dump(json_last_error_msg());
            return null;
        }

        return $response;
    }
}
