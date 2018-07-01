<?PHP

$hostname = gethostname();
require_once "{$hostname}settings.php";

function session_check()
{
	//first connect to site - generate cookie, set it and give to function set_json
        if(!isset($_COOKIE['MYSID'])) {
                $token=md5(rand(0,1000000000)); //calculate string by md5
                setcookie('MYSID', $token);
                $user=array('id'=>NULL,'username'=>"Visitor");
                redis_set_json($token, $user,0);
		//value of cookie - $user array
        }
        else
	{
	//server already knows it's the same server and presents login.php
                $token="MYSID:".$_COOKIE['MYSID'];
	}
//if we clicked rembember - forever else 300 seconds
	$expire = isset($_POST['remember']) ? 0 : 300;
//if we filled login page we autorize result in PHP as set a cookie with token
        if (isset($_POST['username']) and isset($_POST['password']))
                return authorize($_POST['username'],$_POST['password'],$token,$expire);
        else //we have a cookie in a site without POST
                return authorize(NULL,NULL,$token,$expire);
}
function authorize($username,$password, $token, $expire)
{
//if we have username and pass do this
        if ($username!=NULL and $password!=NULL)
        {
                //if ($username=="kalkos" and $password=="qwerty")
                  //      $user=array('id'=>333,'username'=>$username);
                //else
                       // $user=array('id'=>NULL,'username'=>"Visitor");
               // redis_set_json($token,$user,"0");
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
    			else{
                		$password = trim($password);
			}
        		$sql = "select i, login, password, role from login where login = ?";
			$dbs = mysqli_connect(DB_SERVER_S,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
			if ($res = mysqli_prepare($dbs, $sql))
			{
				// bind variables to prepared statement as parameters
				mysqli_stmt_bind_param($res, "s", $pr_usrname);
				// set params
				$pr_usrname = $username;
				if (mysqli_stmt_execute($res)) {
				//store result
					mysqli_stmt_store_result($res);
				//if user exists, verify password
				if (mysqli_stmt_num_rows($res) == 1) {
					// Bind result variables
                            		mysqli_stmt_bind_result($res, $user['id'], $user['username'], $hashed_password, $user['role']);
                            	if (mysqli_stmt_fetch($res)) {
                               	if (password_verify($password, $hashed_password)) {
                                    echo "All works!";
                                    redis_set_json($token, $user, $expire);
                                    return $user;
                                }
				else {
				$user = array();
                                    echo "Pass not valid!";
                                    return $user;
				}
			    } //fetch
                        else {
				$user = array();
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
}
}
        else //user and pass are NULL
	{
                return redis_get_json($token);
	}
}
//this function i don't use i have it in logout.php
function logout($user)
{
        $token=$_COOKIE['MYSID'];
        $user=array('id'=>NULL,'username'=>"Visitor");
        redis_set_json($token,$user,"0");
        return $user;
}
function redis_set_json($key, $val, $expire)
{
	//connect to redis
        $redisClient = new Redis();
	$redisClient->connect( REDIS_SERVER, REDIS_PORT );
	$redisClient->auth(REDIS_PASSWORD); //pass in /etc/redis/redis.conf session in /etc/php/7.0/apache2 session.save_path
      	//zakodowany string JSON
	$value=json_encode($val);
        if ($expire > 0)
                $redisClient->setex($key, $expire, $value); //set expire with given key and value
        else
                $redisClient->set($key, $value); //if expire = foverer only set key and value
        $redisClient->close();
}
function redis_get_json($key)
{
        $redisClient = new Redis();
        $redisClient->connect( REDIS_SERVER, REDIS_PORT );
	$redisClient->auth(REDIS_PASSWORD);
	//odkodowany json
	$ret=json_decode($redisClient->get($key),true);
        $redisClient->close();
        return $ret;
}

function show_menu($user)

{
//control what user we have
    echo '<pre>';

    print_r($user);

    echo '</pre>';

echo '

<nav class="uk-navbar">

    <ul class="uk-navbar-nav">';
//if dont have user just login and home if logged logout and mywall
                if ($user==NULL or $user['id'] != true)
                        echo '<li class="uk-active"><a href="login.php">Login</a></li>';
                else
                        echo '<li class="uk-active"><a href="logout.php">Logout</a></li>
			<li class="uk-active"><a href="mywall.php">My wall</a></li>';
			echo '<li class="uk-parent"><a href="index.php">Home</a></li>

    </ul>

</nav>';

}
