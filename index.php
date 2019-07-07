<?php

	session_start();
	ob_start();

	include_once ("config/koneksi.php");
	include_once ("config/function.php");

	$proteksi	=	isset($_GET["proteksi"]) ? $_GET["proteksi"] : false;
	$folder 	=	isset($_GET["folder"]) ? $_GET["folder"] : false;
	$file		=	isset($_GET["file"]) ? $_GET["file"] : false;

	$session_kode_admin		=	isset($_SESSION["session_kode_admin"]) ? $_SESSION["session_kode_admin"]  : false;
	$session_username		=	isset($_SESSION["session_username"]) ? $_SESSION["session_username"]  : false;
	$session_level			=	isset($_SESSION["session_level"]) ? $_SESSION["session_level"]  : false;
	$session_status_admin	=	isset($_SESSION["session_status_admin"]) ? $_SESSION["session_status_admin"]  : false;

	// Jika admin belum login
	if (!$session_kode_admin) {

		// kita akan mendirect admin kehalaman login dan tidak bisa mengakses halaman utama
		header("location:login.php");

	} # if !$session_kode_admin

?>

<!DOCTYPE html>
<html>
<head>
	<?php include_once ("content/head.php"); ?>
</head>

<body>

	<div class="navbar">

		<?php include_once ("content/navbar.php"); ?>

	</div><!-- .navbar -->

	<div class="main">

		<?php

			$dinamis 	=	"page/$folder/$file.php";

			if (file_exists($dinamis)) {

				include_once ($dinamis);

			}

			else {

				include_once ("content/main.php");
			}

		?>

	</div><!-- .main -->

</body>

</html>

<?php

	mysqli_close($koneksi);
	ob_end_flush();

?>