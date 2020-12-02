<?php

declare(strict_types=1);

use Aws\Credentials\Credentials;
use Aws\Signature\SignatureV4;
use Aws\Sts\StsClient;
use GuzzleHttp\Client;
use SandboxApp\ApiClientConfiguration;
use SandboxApp\Client\AuthClient;
use SandboxApp\Client\OrderClient;
use SandboxApp\GazzelHttpApiTransport;
use SandboxApp\RequestSignature;
use Symfony\Component\Dotenv\Dotenv;

$loader = require __DIR__ . '/vendor/autoload.php';

$dot = new Dotenv();
$dot->usePutenv();
$dot->load(__DIR__ . '/.env');

//// PREPARE REQUEST SIGNING
$awsCredentials = new Credentials(
    getenv('AWS_USER_KEY'),
    getenv('AWS_USER_SECRET')
);

$awsSTSClient = new StsClient([
    'region' => getenv('AWS_REGION'),
    'version' => getenv('AWS_STS_VERSION'),
    'credentials' => $awsCredentials
]);

$awsSignatureV4 = new SignatureV4(
    'execute-api',
    getenv('AWS_REGION')
);

$requestSignature = new RequestSignature(
    $awsSTSClient,
    $awsSignatureV4,
    getenv('AWS_ROLE_ARM')
);

// SET UP YOR API CLIENTS AND DO CALLS
$apiTransport = new GazzelHttpApiTransport(new Client());

//// SET UP AuthClient
$apiClientConfiguration = new ApiClientConfiguration(
    getenv('AUTH_API_HOST')
);
$apiClientConfiguration
    ->setAppClientId(getenv('SP_API_APP_CLIENT_ID'))
    ->setAppClientSecret(getenv('SP_API_APP_CLIENT_SECRET'));

$authClient = new AuthClient(
    $apiTransport,
    $requestSignature,
    $apiClientConfiguration,
);

// GET ACCESS_TOKEN
$oauthResponse = $authClient->refreshAccessToken(getenv('REFRESH_TOKEN'));

//// SET UP OrderClient
$orderClient = new OrderClient(
    $apiTransport,
    $requestSignature,
    new ApiClientConfiguration(
        getenv('BASE_API_HOST')
    ),
    $oauthResponse['access_token']
);

// CALL ORDERS API
var_dump(
    $orderClient->getOrders([getenv('MARKETPLACE_ID')], date("Y-m-d", strtotime( '-1 days' )))
);