<?PHP

$hostname = gethostname();
require_once "{$hostname}settings.php";

function session_check()
{
        if(!isset($_COOKIE['MYSID'])) {
                $token=md5(rand(0,1000000000));
                setcookie('cookie', $token);
                $user=array('id'=>NULL,'username'=>"Visitor");
                redis_set_json($token, $user,0);
        }
        else
                $token=$_COOKIE['MYSID'];

		$expire = isset($_POST['remember']) ? 0 : 600;

        if (isset($_POST['username']) and isset($_POST['password']))
                return authorize($_POST['username'],$_POST['password'],$token);
        else
                return authorize(NULL,NULL,$token);
}
function authorize($username,$password, $token)
{
        if ($username!=NULL and $password!=NULL)
        {
                /*if ($username=="kalkos" and $password=="qwerty")
                        $user=array('id'=>333,'username'=>$username);
                else
                        $user=array('id'=>NULL,'username'=>"Visitor");
                redis_set_json($token,$user,"0");*/
		$user = array();


	if($_SERVER["REQUEST_METHOD"] == "POST"){

    if (empty(trim($username))) {
	echo "Empty username!";
    }
    else
	{
		$user['username'] = trim($username);
	}
    if (empty(trim($password))) {

                echo "Please enter your password!";

    }
    else {

                $password = trim($password);

		$pass = password_hash($password, PASSWORD_DEFAULT);
   }

        $sql = "select i,login,password,role from login where login = ?";

	if ($res = mysqli_prepare($dbs, $sql))
		{
			mysqli_stmt_bind_param($res, "s", $pr_usrname);
			$pr_usrname = $username;

			if (mysqli_stmt_execute($res)) {

				mysqli_stmt_store_result($res);

			if (mysqli_stmt_num_rows($res) == 1) {

                            mysqli_stmt_bind_result($res, $user['id'], $user['username'], $hashed_password, $user['isAdmin']);

                            if (mysqli_stmt_fetch($stmt)) {

                                if (password_verify($pass, $hashed_password)) {

                                    echo "All works!";
                                    redis_set_json($token, $user, $expire);
                                    return $user;

                                }
				else {

                                    echo "Pass not valid!";
                                    return $user;
				}
			    } //fetch
                        else {

                       		 echo "User not exists";
			       	 return $user;

                    	}
		} //numrows
		else {

                	 echo "Try again later";

         		}
		} //execute
                mysqli_stmt_close($res);
		mysqli_close($dbs);
        }
        else
                return redis_get_json($token);
}
function logout($user)
{
        $token=$_COOKIE['MYSID'];
        $user=array('id'=>NULL,'username'=>"Visitor");
        redis_set_json($token,$user,"0");
        return $user;
}
function redis_set_json($key, $val, $expire)
{
        $redisClient = new Redis();
	$redisClient->connect( REDIS_SERVER, REDIS_PORT );
	$redisClient->auth(REDIS_PASSWORD);
      	$value=json_encode($val);
        if ($expire > 0)
                $redisClient->setex($key, $expire, $value );
        else
                $redisClient->set($key, $value);
        $redisClient->close();
}
function redis_get_json($key)
{
        $redisClient = new Redis();
        $redisClient->connect( REDIS_SERVER, REDIS_PORT );
	$redisClient->auth(REDIS_PASSWORD);
	$ret=json_decode($redisClient->get($key),true);
        $redisClient->close();
        return $ret;
}

function show_menu($user)

{
    echo '<pre>';

    print_r($user);

    echo '</pre>';

echo '

<nav class="uk-navbar">

    <ul class="uk-navbar-nav">';

                if ($user==NULL and $user['username'] == NULL)
		{
                        echo '<li class="uk-active"><a href="login.php">Login</a></li>';
		}
                else
		{
                        echo '<li class="uk-active"><a href="logout.php">Logout</a></li>
			<li class="uk-active"><a href="addpost.php">Add new post</a></li>';
			echo '<li class="uk-parent"><a href="index.php">Home</a></li>

    </ul>

</nav>';
		}
}
?>
