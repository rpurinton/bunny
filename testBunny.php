#!/usr/bin/env php
<?php

namespace RPurinton\Bunny;

require_once(__DIR__ . '/vendor/autoload.php');

use RPurinton\Config;
use RPurinton\Bunny\Async\Client as AsyncClient;
use React\EventLoop\Loop;
use \Exception;

$config = Config::get("Bunny", [
    "host"      => "string",
    "vhost"     => "string",
    "user"      => "string",
    "password"  => "string",
    "port"      => "int",
    "heartbeat" => "int"
]);

$client = new Client($config);
$client->connect();
$channel = $client->channel();
$channel->queueDeclare("bunnytest", false, false, false, true);
$channel->publish("bunnytest", [], "", "bunnytest");
$channel->close();

$loop = Loop::get();
$client = new AsyncClient($loop, $config);
$client->connect()
    ->then(getChannel(...))
    ->then(consume(...))
    ->catch(function (\Throwable $e): void {
        throw new Exception("RabbitMQ connection/error: " . $e->getMessage());
    });

function getChannel(AsyncClient $client)
{
    return $client->channel();
}

function consume(Channel $channel): void
{
    $channel->queueDeclare("bunnytest", false, false, false, true);
    $channel->qos(0, 1);
    $channel->consume(
        function (Message $message, Channel $channel): void {
            $channel->ack($message);
            if ($message->content === "bunnytest") {
                echo "Success!\n";
                $channel->close();
                exit(0);
            } else {
                echo "Failure?!\n";
                echo "Expected 'bunnytest', got '{$message->content}'\n";
                $channel->close();
                exit(1);
            }
        },
        "bunnytest" // Queue name
    );
}
