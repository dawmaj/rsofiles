<?PHP
	ob_start();
	require_once "functions.php";

	$user = session_check();

	if (isset($user['id'])) {

    		header("location: index.php");

    		exit;

	}
?>

<html>

<head>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/css/uikit.min.css" />

        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/js/uikit.min.js"> </script>

</head>

<body>

<?PHP show_menu($user); ?>
<form method="post" action="login.php" class="uk-form">

    <fieldset data-uk-margin>

        <legend>Log in</legend>

        <input name="username" type="text" placeholder="username">

        <input name="password" type="password" placeholder="password">
	<br> Rembember me!
	<input type="checkbox" name="remember" value="Yes">
        <br>
	<button class="uk-button uk-button-primary uk-button-large" name ="send"> Send to login! </button>

    </fieldset>

</form>



</body>

</html>
