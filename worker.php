<?php

$host = gethostname();

require_once "{$host}settings.php";

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection(RABBIT_SRV, RABBIT_PORT, RABBIT_USER, RABBIT_PASS);

$channel = $connection->channel();

$channel->queue_declare('posts', false, true, false, false);

$message = $channel->basic_get('posts');

if (empty($message)) {

        $channel->close();

        $connection->close();

        return false;

    }

    $channel->basic_ack($message->delivery_info['delivery_tag']);

    $post = json_decode($message->body, true);
    $post = array_merge($post, $post);

$channel->close();

$connection->close();

return $post;
