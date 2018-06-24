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



$channel->queue_declare('task_queue', false, true, false, false);



$data = implode(' ', array_slice($argv, 1));

if (empty($data)) {

    $data = "Hello World!";

}

$msg = new AMQPMessage(

    $data,

    array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)

);



$channel->basic_publish($msg, '', 'task_queue');



echo ' [x] Sent ', $data, "\n";



$channel->close();

$connection->close();
	$post = $_POST['posts'];
	$sql = "INSERT INTO posts (id,post) VALUE ('".$user['id']."','$post')";

	$dbm = mysqli_connect(DB_SERVER_M,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

	$res = mysqli_query($dbm, $sql);
	
	mysqli_close($dbm);
}
?>
<form method="post" action="addpost.php">
	<br><input type="text" name="posts" maxlength="255"></br>
	<br><input type="submit" name="send"value="SEND POST"></br>
</form>
POSTY:
<br>

<?PHP
  $posty = array();
  $sql1 = "select * from posts ORDER BY date DESC LIMIT 10";
  $dbs = mysqli_connect(DB_SERVER_S,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

  if ($ifRedisexists = redis_get_json("last_10_posts") === NULL)
  {
	//execute query if we don't have 10 posts in Redis
	$res1 = mysqli_query($dbs,$sql1);
	$arraywithPosts = array();
	while($row = mysqli_fetch_array($res1)) {
		$arraywithPosts[] = $row;
	}
	redis_set_json("last_10_posts",$arraywithPosts,30);
 }
  else
   {
	$posty =  redis_get_json("last_10_posts");
   }

   foreach ($posty as &$mypost)
	{
		echo $mypost['post']."<br>";
	}

mysqli_close($dbs);
?>
</body>
</html>
