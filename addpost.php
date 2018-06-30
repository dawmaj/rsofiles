<?PHP
        require_once('functions.php');
	$host = gethostname();
	require_once "{$host}settings.php";
	$user=session_check();

    	if (!isset($user['id'])) {

        header("location: index.php");

        exit;

    	}
	use PhpAmqpLib\Connection\AMQPStreamConnection;

	use PhpAmqpLib\Message\AMQPMessage;
	error_reporting(0);
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

$post = $_POST['posts'];

$connection = new AMQPStreamConnection(RABBIT_SRV, RABBIT_PORT, RABBIT_USER, RABBIT_PASS);

$channel = $connection->channel();


$channel->queue_declare('posts', false, true, false, false);


$data = implode(' ', array_slice($argv, 1));

$data = $post;

$msg = new AMQPMessage(

        $data, ['content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
);


$channel->basic_publish($msg, '', 'posts');

$channel->close();

$connection->close();

$sql = "INSERT INTO posts (id,post) VALUE ('".$user['id']."','$post')";

$dbm = mysqli_connect(DB_SERVER_M,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

$res = mysqli_query($dbm, $sql);

mysqli_close($dbm);

}
?>
<?PHP show_menu($user); ?>

<form method="post" action="addpost.php">
		<h2><span class="uk-text-danger">WRITE YOUR POST HERE</span></h2>
		<br><input type="text" name="posts" maxlength="255"></br>
		<br><input type="submit" name="send"value="SEND POST"></br>
		<br><span class="uk-label-success">POSTS:</span></br>
</form>

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
	redis_set_json("last_10_posts",$arraywithPosts,10);
 }
  else
   {
	$posty =  redis_get_json("last_10_posts");
   }

   foreach ($posty as &$mypost)
	{
		echo "This post is described by user with ID: ".$mypost['id']." text: ".$mypost['post']."<br>";
	}

mysqli_close($dbs);
?>
</body>
</html>
