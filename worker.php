<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;


$connection = new AMQPStreamConnection('192.168.100.10', 5672, 'admin', 'admin');

$channel = $connection->channel();


$channel->queue_declare('posts', false, true, false, false);


echo " [*] Waiting for messages. To exit press CTRL+C\n";


$callback = function ($msg) {

    echo ' [x] Received ', $msg->body, "\n";

    sleep(substr_count($msg->body, '.'));

    echo " [x] Done\n";

    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);

};



$channel->basic_qos(null, 1, null);

$channel->basic_consume('posts', '', false, false, false, false, $callback);



while (count($channel->callbacks)) {

    $channel->wait();

}



$channel->close();

$connection->close();
