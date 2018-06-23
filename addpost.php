<?PHP
        require_once('functions.php');
	$host = gethostname();
	require_once "{$host}settings.php";

    	$user=session_check();

    	if (!isset($user['id'])) {

        header("location: index.php");

        exit;

    	}

	require_once __DIR__ . '/vendor/autoload.php';

	use PhpAmqpLib\Connection\AMQPStreamConnection;

	use PhpAmqpLib\Message\AMQPMessage;

?>
<html>
<head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/css/uikit.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/js/uikit.min.js"> </script>
</head>
<body>
<?PHP
if($_SERVER["REQUEST_METHOD"] == "POST")
{


$connection = new AMQPStreamConnection('192.168.100.10', 5672, 'admin', 'admin');

$channel = $connection->channel();

$post = $_POST['posts'];


$channel->queue_declare('task_queue', false, true, false, false);



$posts = implode('', array_slice($argv, 1));

if (empty($posts)) {

    $posts = $post;

}

$msg = new AMQPMessage(

    $posts,

    array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)

);



$channel->basic_publish($msg, '', 'task_queue');



echo ' [x] Sent ', $posts, "\n";



$channel->close();

$connection->close();
	//$sql = "INSERT INTO posts (post) VALUE ('$post')";
	// $dbm = mysqli_connect(DB_SERVER_M,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
	//$res = mysqli_query($dbm, $sql);
}

//mysqli_close($dbm);
//mysqli_close($dms);
?>
<form method="post" action="addpost.php">
	<br><input type="text" name="posts" maxlength="255"></br>
	<br><input type="submit" name="send"value="SEND POST"></br>
</form>
POSTY:

<?PHP

  $sql1 = "SELECT * FROM posts";
  $dbs = mysqli_connect(DB_SERVER_S,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
  $res1 = mysqli_query($dbs,$sql1);
  redis_set_json("last_10_posts",$result,360);
while($row = mysqli_fetch_array($res1)) {

    $p = $row['post'];

    echo $p."<br>";

}
?>
</body>
</html>
