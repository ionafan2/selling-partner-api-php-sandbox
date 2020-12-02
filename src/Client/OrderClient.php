<?php

declare(strict_types=1);

namespace SandboxApp\Client;

use SandboxApp\ApiClientConfiguration;
use SandboxApp\ApiTransportInterface;
use SandboxApp\RequestSignature;

class OrderClient extends AbstractClient
{
    private string $accessToken;

    public function __construct(
        ApiTransportInterface $apiTransport,
        RequestSignature $requestSignature,
        ApiClientConfiguration $config,
        string $accessToken
    ) {
        parent::__construct($apiTransport, $requestSignature, $config);

        $this->accessToken = $accessToken;
    }

    public function getOrders(array $marketplaceIds, string $createdAfter): ?array
    {
        $response = $this->send(
            'GET',
            '/orders/v0/orders',
            [
                'MarketplaceIds' => implode(',', $marketplaceIds),
                'CreatedAfter' => $createdAfter
            ],
            ["x-amz-access-token" => $this->accessToken]
        );

        if (0 === count((array)$response['payload'])) {
            return null;
        }

        return $response['payload'];
    }
}
