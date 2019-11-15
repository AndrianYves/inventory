<?php
	session_start();
	include 'inc/conn.php';

	if(isset($_POST['login'])){
		$user = mysqli_real_escape_string($conn, $_POST['user']);
		$password = mysqli_real_escape_string($conn, $_POST['password']);

		$sql = "SELECT * FROM admins WHERE username= '$user'";
		$query = $conn->query($sql);

		if($query->num_rows < 1){
			$_SESSION['error'] = 'Cannot find account';
		}
		else{
			$row = $query->fetch_assoc();
			if(password_verify($password, $row['password'])){
				$_SESSION['admin'] = $row['id'];
			}
			else{
				$_SESSION['error'] = 'Incorrect password';
			}
		}
	} else {
		$_SESSION['error'] = 'Input credentials first';
	}

	header('location: index.php');

?>