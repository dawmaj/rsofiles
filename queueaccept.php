<?php
$host = gethostname();
require_once "functions.php";

require_once "{$host}settings.php";
$user = session_check();

use PhpAmqpLib\Connection\AMQPStreamConnection;

function showPost()
{
$connection = new AMQPStreamConnection(RABBIT_SRV, RABBIT_PORT, RABBIT_USER, RABBIT_PASS,'/');

$channel = $connection->channel();

$channel->queue_declare('posts_list', false, true, false, false);

$channel->exchange_declare('get_posts', 'direct', false, true, false);

$channel->queue_bind('posts_list', 'get_posts');

$message = $channel->basic_get('posts_list');

$channel->basic_ack($message->delivery_info['delivery_tag']);

if (empty($message)) {

	return "Empty queue!";
}

$post = json_decode($message->body, true);

$channel->close();

$connection->close();
return $post;

}
?>
<form method="post" action="queueaccept.php">
	<?PHP 	$verify = showPost();
		echo "Post to delete? ".$verify;
		

		
        ?>
	<br>
	<input type="submit" name="yes" value="YES">
	<br>
	<input type="submit" name="no" value="NO">

</form>
