<?php
	$conn = mysqli_connect('localhost', 'root', '', 'forkndagger');

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}

	error_reporting(0);	
	//mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>