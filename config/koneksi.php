<?php

	global $koneksi;

	$servername	=	"localhost";
	$username	=	"root";
	$password 	=	"";
	$dbname 	=	"database_chicken";

	$koneksi 	=	mysqli_connect($servername,$username,$password,$dbname);

	if (!$koneksi) {

		die("
			Koneksi dengan database gagal : ".mysqli_connect_errno()." - ".mysqli_connect_error()
		);

	}

	// else { echo "Koneksi ke database berhasil !"; }

?>