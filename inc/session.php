<?php
	session_start();
	include 'inc/conn.php';;

	if(isset($_SESSION['admin'])){
		$query = $conn -> prepare("SELECT * FROM admins WHERE id = ? ");
		$query -> bind_param("s", $variable);
		$variable = $_SESSION['admin'];
		$query -> execute();
		$result = $query->get_result();
		$user = mysqli_fetch_assoc($result);
		$role = $user['role'];
	}
	else{
		header('location: home.php');
		exit();
	}
	
?>