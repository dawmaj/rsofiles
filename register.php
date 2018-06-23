<?php  
	$hostname = gethostname();
	require_once "{$hostname}settings.php";
        require_once('functions.php');
	//require_once('dbConfig.php');

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

<?php

  if($_SERVER["REQUEST_METHOD"] == "POST")
 {
  $login = $_POST['userName'];
  $name = $_POST['firstName'];
  $surname = $_POST['lastName'];
  $pesel = $_POST['PESEL'];
  $nip = $_POST['NIP'];
  $password = $_POST['password'];
  $chkpassword = $_POST['confirm_password'];
  $email = $_POST['userEmail'];
  $role = $_POST['role'];
  $image=$_FILES['image']['name'];
  $target = "images/".basename($image);
    if ($password == $chkpassword)
{
    $password = password_hash($password,PASSWORD_DEFAULT);
    $sql = "INSERT INTO login (login,imie,nazwisko,pesel,nip,password,email,role,avatar) VALUES ('$login','$name','$surname','$pesel','$nip','$password','$email','$role','$image')";
    $dbm = mysqli_connect(DB_SERVER_M,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    $result = mysqli_query($dbm,$sql);

    if (move_uploaded_file($_FILES['image']['tmp_name'],$target))
	{
		echo "OK!";
	}
    else
	{
		echo "Image uploaded fail";
	}

}
else
{
	echo "Password not the same!";
}
 }

mysqli_close($dbm);
?>

<form name="frmRegistration" method="post" action="register.php" enctype="multipart/form-data">
	<table border="0" width="500" align="center" class="demo-table">
		<tr>
			<td>User Name</td>
			<td><input type="text"  name="userName" required></td>
		</tr>
		<tr>
			<td>First Name</td>
			<td><input type="text"  name="firstName" required></td>
		</tr>
		<tr>
			<td>Last Name</td>
			<td><input type="text" name="lastName" required></td>
		</tr>
		<tr>
			<td>PESEL</td>
			<td><input pattern="[0-9]{11}" type="text"  name="PESEL" required></td>
		</tr>
		<tr>
			<td>NIP</td>
			<td><input pattern="[0-9]{10}" type="text"  name="NIP" required></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input type="password"  name="password" required></td>
		</tr>
		<tr>
			<td>Confirm Password</td>
			<td><input type="password"  name="confirm_password" required></td>
		</tr>
		<tr>
			<td>Email</td>
			<td><input type="email"  name="userEmail" required></td>
		</tr>
		<tr>
			<td>Avatar</td>
			<td><input type="file" name="image"></td>
		</tr>
		<tr>
			<td>Admin?</td>
			<td><input type="checkbox" value='1' name="role"></td>
		</tr>
		<tr>
			<td colspan=2>
			<input type="submit" name="save"></td>
		</tr>
	</table>
</form>
</body>
</html>

