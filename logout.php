<?php

	session_start();

	// membaca session admin yang tersimpan di session cookie
	$_SESSION["session_kode_admin"];
	$_SESSION["session_username"];
	$_SESSION["session_level"];
	$_SESSION["session_status_admin"];

	// menghapus session admin yang tersimpan di session cookie
	unset($_SESSION["session_kode_admin"]);
	unset($_SESSION["session_username"]);
	unset($_SESSION["session_level"]);
	unset($_SESSION["session_status_admin"]);

	session_unset();
	session_destroy();

	// mendirect user untuk menuju halaman login.php
	header("location:login.php");

?>