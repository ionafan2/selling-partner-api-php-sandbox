<?php



declare(strict_types=1);

namespace SandboxApp;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ApiTransportInterface
{
    public function send(RequestInterface $request, array $options): ResponseInterface;
}
