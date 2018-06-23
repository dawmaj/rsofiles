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

		

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $login = trim($_POST['username']);

    $pass = trim($_POST['password']);

   

    $sql = "select * from login where login = '".$login."'";

    $rs = mysqli_query($dbs,$sql);

    $numRows = mysqli_num_rows($rs);

   

    if($numRows  == 1){

        $row = mysqli_fetch_assoc($rs);

        if(password_verify($pass,$row['password'])){

            echo "Password verified";

            if ($row['role'] == 1)

            {

               

                               

                header("refresh:2;url=queueaccept.php");

            }

            else

            {

                header("refresh:2;url=addpost.php");

            }

        }

        else{

            echo "Wrong Password";

        }

    }

    else{

        echo "No User found";

    }

}

		mysqli_close($dbs);
                return $user;
        }
        else
                return redis_get_json($token);
}
function logout($user)
{
        $token=$_COOKIE['cookie'];
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

                if ($user==NULL or $user['username']==NULL)

                        echo '<li class="uk-active"><a href="login.php">Login</a></li>';

                else

                        echo '<li class="uk-active"><a href="logout.php">Logout</a></li>';
			echo '<li class="uk-active"><a href="addpost.php">Add new post</a></li>';

			echo '<li class="uk-parent"><a href="index.php">Home</a></li>

    </ul>

</nav>';

}
?>
