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
	//error_reporting(0);
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

$sql = "INSERT INTO posts (id,post) VALUE ('".$user['id']."','$post')";

$dbm = mysqli_connect(DB_SERVER_M,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

$res = mysqli_query($dbm, $sql);

mysqli_close($dbm);
//dodanie do bazy user id i posta - data dodaje sie automatycznie w db

//zwraca JSON o wartosci post
$post_json = json_encode($post);
//polaczenie z kolejka o serwerze, porcie, userze, pass i VHOST = /
$connection = new AMQPStreamConnection(RABBIT_SRV, RABBIT_PORT, RABBIT_USER, RABBIT_PASS, '/');
//like socket
$channel = $connection->channel();
//make sure that RabbitMQ will never lose our queue. In order to do so, we need to declare it as durable
$channel->queue_declare('posts_list', false, true, false, false);
//wymiana  bezposrednia do kilku kolejek
$channel->exchange_declare('get_posts', 'direct', false, true, false);
//tell the exchange to send messages to our queue
$channel->queue_bind('posts_list', 'get_posts');
//msg as json  as a plain text delivery mode 'persistent' that are delivered to 'durable' queues will be logged to disk
$msg = new AMQPMessage(

        $post_json, ['content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
);
//publikuj do kolejki get_posts
$channel->basic_publish($msg, 'get_posts');
//odkodowanie wiadomosci
$decode = json_decode($msg->body, true);
//sprawdzenie czy wyslalo
echo "Send to queue: ".$decode;

$channel->close();

$connection->close();

}
?>
<?PHP show_menu($user); ?>

<form method="post" action="addpost.php">
		<h2><span class="uk-text-danger">WRITE YOUR POST HERE</span></h2>
		<br><input type="text" name="posts" maxlength="255"></br>
		<br><input type="submit" name="send"value="SEND POST"></br>
		<br><a href="addpost.php"><input type="button" value="REFRESH WALL"></a></br>
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
	//last_10_posts = store arraywithPosts for 20 seconds
	redis_set_json("last_10_posts",$arraywithPosts,20);
 }
  else
   {
	//get 10 posts if we have last_10_posts in redis
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
