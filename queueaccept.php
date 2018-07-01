
<?php
ob_start();
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
//never lose this queue
$channel->queue_declare('posts_list', false, true, false, false);

$channel->exchange_declare('get_posts', 'direct', false, true, false);

$channel->queue_bind('posts_list', 'get_posts');

$message = $channel->basic_get('posts_list');
//basic ack with delivery info  that associate to whick message ack belongs to
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
<?PHP
		show_menu($user);
?>
<form method="post" action="queueaccept.php">
<?PHP
	$verify = showPost();
	echo "Remove next post in queue?";
	// empty post not deleted
	if (isset($_POST['yes']) and $verify == "Empty!")

	{

		echo '<br>';

		echo "No posts in queue";

	}
	//delete post
	elseif (isset($_POST['yes']))

	{
		echo "Post with text: ".$verify;
		$sql = "DELETE FROM posts WHERE post='$verify';";

		$dbm = mysqli_connect(DB_SERVER_M,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

		$result = mysqli_query($dbm,$sql);

		echo '<br>';
		echo "Post deleted!";
	}
	//stay post
	elseif (isset($_POST['no']))

	{
		echo '<br>';
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
