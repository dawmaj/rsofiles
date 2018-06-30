<?php

    // Initialize the session

    require_once('functions.php');

    $user=session_check();

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
<?PHP
	if ($user['role'] == 1)
	{
	show_menu($user);
	echo '<br><a href="queueaccept.php"><input type="button" value="ACCEPT POST"></a></br>';
	echo '<br><a href="register.php"><input type="button" value="REGISTER"></a></br>';
	}
	else
	{
	show_menu($user);
	echo '<br><a href="addpost.php"><input type="button" value="ADD POSTS"></a></br>';
	}
?>
</body>
</html>

