<?php
	session_start();
	include 'inc/conn.php';;
	date_default_timezone_set("Asia/Manila");

	if(isset($_SESSION['admin'])){
		$query = $conn -> prepare("SELECT * FROM admins WHERE id = ? ");
		$query -> bind_param("s", $variable);
		$variable = $_SESSION['admin'];
		$query -> execute();
		$result = $query->get_result();
		$user = mysqli_fetch_assoc($result);
		$role = $user['role'];
		$today = date('Y-m-d H:i');

	}
	else{
		header('location: home.php');
		exit();
	}
	
?>