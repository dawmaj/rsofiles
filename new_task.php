<?php

$host = gethostname();

require_once "{$host}settings.php";

use PhpAmqpLib\Connection\AMQPStreamConnection;

use PhpAmqpLib\Message\AMQPMessage;



$connection = new AMQPStreamConnection(RABBIT_SRV, RABBIT_PORT, RABBIT_USER, RABBIT_PASS);

$channel = $connection->channel();



$channel->queue_declare('posts', false, true, false, false);



$data = implode(' ', array_slice($argv, 1));

if (empty($data)) {

    $data = "Hello World!";

}

$msg = new AMQPMessage(

    $data,

    array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)

);



$channel->basic_publish($msg, '', 'posts');



echo ' [x] Sent ', $data, "\n";



$channel->close();

$connection->close();
