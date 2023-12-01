<?php

error_reporting(E_ERROR);

require('vendor/autoload.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$host = getenv('RABBITMQ_HOST');
$user = getenv('RABBITMQ_USER');
$pass = getenv('RABBITMQ_PASSWORD');
$vhost = getenv('RABBITMQ_VHOST');
$seconds = getenv('RABBITMQ_SECONDS', 1);

while (true) {

    $time = date("d/m/Y H:i:s");

    try {
        $connection = new AMQPStreamConnection($host, 5672, $user, $pass, $vhost);

        $channel = $connection->channel();


        $channel->exchange_declare('test_exchange', 'direct', false, false, false);
        $channel->queue_declare('test_queue', false, false, false, false);
        $channel->queue_bind('test_queue', 'test_exchange', 'test_key');

        $msg = new AMQPMessage('Hello World!');

        $channel->basic_publish($msg, 'test_exchange', 'test_key');

        echo "{$time}: Conectado com sucesso!\n";

        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
        };

        $channel->basic_consume('test_queue', '', false, true, false, false, $callback);

        $channel->close();
        $connection->close();
    } catch (\Throwable $th) {
        echo "{$time}: {$th->getMessage()}\n";
    }

    sleep($seconds);
}
