<?php

namespace PerSeo\Middleware\ForwardedProto;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Factory\AppFactory;

class ForwardedProto implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $uri = $request->getUri();
        $scheme = $request->getHeaderLine('X-Forwarded-Proto') ?: $uri->getScheme();
        if ($scheme !== $uri->getScheme()) {
            $uri = $uri->withScheme($scheme);
            $request = $request->withUri($uri);
        }
        return $handler->handle($request);
    }
}