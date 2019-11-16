<?php
	session_start();
	include 'inc/conn.php';

	if(isset($_POST['login'])){
		$user = mysqli_real_escape_string($conn, $_POST['user']);
		$password = mysqli_real_escape_string($conn, $_POST['password']);

		$sql = "SELECT * FROM admins WHERE username= '$user'";
		$query = $conn->query($sql);

		if($query->num_rows < 1){
			$_SESSION['error'] = 'Invalid Username/Password';
		}
		else{
			$row = $query->fetch_assoc();
			if(password_verify($password, $row['password'])){
				$timestamp = date("Y-m-d H:i:s");
				$result1 = mysqli_query($conn,"UPDATE admins SET lastlogin='$timestamp' WHERE username='$user'");
				
				$_SESSION['admin'] = $row['id'];
			}
			else{
				$_SESSION['error'] = 'Invalid Username/Password';
			}
		}
	} else {
		$_SESSION['error'] = 'Input credentials first';
	}
	
	header('location: index.php');

?>