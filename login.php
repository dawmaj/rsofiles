<?PHP
	ob_start();
	$hostname = gethostname();
	require_once "functions.php";

	$user = session_check();

	if (isset($user['id'])) {

    		header("location: index.php");

    		exit;

	}
	require_once "{$hostname}settings.php";
?>

<html>

<head>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/css/uikit.min.css" />

        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/js/uikit.min.js"> </script>

</head>

<body>

<?PHP

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
			
			session_start();

                        $_SESSION['username'] = $login;
			$_SESSION['loggedin'] = true;

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
?>
<?PHP show_menu($user); ?>
<form method="post" action="login.php" class="uk-form">

    <fieldset data-uk-margin>

        <legend>Log in</legend>

        <input name="username" type="text" placeholder="username">

        <input name="password" type="password" type="password" placeholder="password">

        <button class="uk-button" name ="send">Send to login!</button>

    </fieldset>

</form>



</body>

</html>
