<?PHP
        require_once('functions.php');
	$host = gethostname();
	require_once "{$host}settings.php";
	$user=session_check();
?>
<html>
<head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/css/uikit.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/js/uikit.min.js">
</script>
</head>
<?PHP
	$dbs = mysqli_connect(DB_SERVER_S,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

	$sql = "SELECT avatar from login WHERE i='$user[id]'";

        $result = mysqli_query($dbs,$sql);

        $row = mysqli_fetch_array($result);

        $image = (is_null($row)) ? "default" : $row['avatar'];

	$image_src = "images/".$image;
?>
<body>
<?PHP show_menu($user); ?>
Hello <?PHP echo $user['username']; ?>! This is your website.

<?PHP echo '<img src="'.$image_src.'" height="100" width="100">'; ?>
</body>
</html>

