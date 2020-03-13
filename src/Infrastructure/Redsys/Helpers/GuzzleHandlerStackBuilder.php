<?php
declare(strict_types=1);

namespace Codehell\Psd2\Infrastructure\Redsys\Helpers;


use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Log;

final class GuzzleHandlerStackBuilder
{
    public static function create()
    {
        $stack = new HandlerStack();
        $stack->setHandler(new CurlHandler());

        $stack->push(
            Middleware::log(
                Log::getFacadeRoot(),
                new MessageFormatter(MessageFormatter::DEBUG)
            )
        );
        return $stack;
    }
}
