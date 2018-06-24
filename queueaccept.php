<?PHP
	$host = gethostname();
	require_once "{$host}settings.php";
	require_once "functions.php";
	$user = session_check();
	if (!isset($user['id'])) {
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
<input class="uk-button uk-button-primary" value="REJECT POST">
<input class="uk-button uk-button-primary" value="REMAIN POST">
</body>
</html>
