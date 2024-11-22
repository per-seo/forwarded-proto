<?php

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Server\RequestHandlerInterface;
use PerSeo\Middleware\ForwardedProto\ForwardedProto;

class ForwardedProtoMiddlewareTest extends TestCase
{
    public function testProcessWithXForwardedProtoHeader()
    {
        // Mock URI
        $uriMock = $this->createMock(UriInterface::class);
        $uriMock->expects($this->once())
            ->method('withScheme')
            ->with('https')
            ->willReturnSelf();

        // Mock Request
        $requestMock = $this->createMock(ServerRequestInterface::class);
        $requestMock->expects($this->once())
            ->method('getHeaderLine')
            ->with('X-Forwarded-Proto')
            ->willReturn('https');
        $requestMock->expects($this->once())
            ->method('getUri')
            ->willReturn($uriMock);
        $requestMock->expects($this->once())
            ->method('withUri')
            ->with($uriMock)
            ->willReturnSelf();

        // Mock Response
        $responseMock = $this->createMock(ResponseInterface::class);

        // Mock RequestHandler
        $handlerMock = $this->createMock(RequestHandlerInterface::class);
        $handlerMock->expects($this->once())
            ->method('handle')
            ->with($requestMock)
            ->willReturn($responseMock);

        // Test Middleware
        $middleware = new ForwardedProto();
        $response = $middleware->process($requestMock, $handlerMock);

        // Assert response is returned as expected
        $this->assertSame($responseMock, $response);
    }

    public function testProcessWithoutXForwardedProtoHeader()
    {
        // Mock URI
        $uriMock = $this->createMock(UriInterface::class);

        // Mock Request
        $requestMock = $this->createMock(ServerRequestInterface::class);
        $requestMock->expects($this->once())
            ->method('getHeaderLine')
            ->with('X-Forwarded-Proto')
            ->willReturn('');
        $requestMock->expects($this->once())
            ->method('getUri')
            ->willReturn($uriMock);
        $requestMock->expects($this->never())
            ->method('withUri');

        // Mock Response
        $responseMock = $this->createMock(ResponseInterface::class);

        // Mock RequestHandler
        $handlerMock = $this->createMock(RequestHandlerInterface::class);
        $handlerMock->expects($this->once())
            ->method('handle')
            ->with($requestMock)
            ->willReturn($responseMock);

        // Test Middleware
        $middleware = new ForwardedProto();
        $response = $middleware->process($requestMock, $handlerMock);

        // Assert response is returned as expected
        $this->assertSame($responseMock, $response);
    }
}