<?php



require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

use PhpAmqpLib\Message\AMQPMessage;



$connection = new AMQPStreamConnection('192.168.100.10', 5672, 'admin', 'admin');

$channel = $connection->channel();



$channel->exchange_declare('logs', 'fanout', false, false, false);



$data = implode(' ', array_slice($argv, 1));

if (empty($data)) {

    $data = "info: Hello World!";

}

$msg = new AMQPMessage($data);



$channel->basic_publish($msg, 'logs');



echo ' [x] Sent ', $data, "\n";



$channel->close();

$connection->close();
