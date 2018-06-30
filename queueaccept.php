
<?php

$host = gethostname();
require_once "functions.php";

require_once "{$host}settings.php";

$user = session_check();

if (!isset($user['id']) or $user['role'] != 1) {

    	header("location: index.php");

    	exit;

}

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

	return "Empty!";
}

$post = json_decode($message->body, true);

$channel->close();

$connection->close();
return $post;

}
?>
<html>
<head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/css/uikit.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/js/uikit.min.js"> </script>
</head>
<body>
<form method="post" action="queueaccept.php">
	<?PHP 	show_menu($user);
		ob_start();
		$verify = showPost();
		echo "Post to delete? ".$verify;
	if (isset($_POST['yes']) and $verify == "Empty!")
	{
		echo '<br>';
		echo "No posts in queue";
	}
	elseif (isset($_POST['yes']))
	{
		$sql = "DELETE FROM posts WHERE post='$verify';";
		$dbm = mysqli_connect(DB_SERVER_M,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		$result = mysqli_query($dbm,$sql);
		echo "Post deleted!";
	}
	elseif (isset($_POST['no']))
	{
		echo "Post stays!";
		header("refresh:2;url=queueaccept.php");
	}
        ?>
	<br>
	<input type="submit" name="yes" value="YES">
	<br>
	<input type="submit" name="no" value="NO">

</form>
</body>
</html>
