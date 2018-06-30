<?php

$host = gethostname();
require_once "functions.php";

require_once "{$host}settings.php";

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection(RABBIT_SRV, RABBIT_PORT, RABBIT_USER, RABBIT_PASS,'/');

$channel = $connection->channel();

$channel->queue_declare('posts_list', false, true, false, false);

$channel->exchange_declare('get_posts', 'direct', false, true, false);

$channel->queue_bind('posts_list', 'get_posts');

$message = $channel->basic_get('posts_list');

$channel->basic_ack($message->delivery_info['delivery_tag']);

if (empty($message)) {

        $channel->close();

        $connection->close();

        return "NO POST IN QUEUE";

}

$post = json_decode($message->body, true);

$channel->close();

$connection->close();

echo $post;

?>
<form method="post" action="worker.php">
	<input type="submit" name="send" placeholder="POBIERZ POST DO WERYFIKACJI">
</form>
