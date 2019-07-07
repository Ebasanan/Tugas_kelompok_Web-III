<?php

	session_start();
	ob_start();

	include_once ("config/koneksi.php");
	include_once ("config/function.php");

	$session_kode_admin		=	isset($_SESSION["session_kode_admin"]) ? $_SESSION["session_kode_admin"] : false;

	// Jika admin sudah login maka akan diarahkan ke dalam halaman utama
	if ($session_kode_admin) {
		header("location:".base_url."?proteksi=akses");
	}

?>
<!DOCTYPE html>
<html>
<head>

	<title>Login Chicken</title>

	<link rel="icon" href="<?php echo base_url."image/favicon.ico"; ?>">

	<link href="<?php echo base_url."css/style.css"; ?>" rel="stylesheet">

	<link href="<?php echo base_url."css/login.css"; ?>" rel="stylesheet">

</head>

<body>

	<form method="post">

		<div class="imgcontainer">

			<img src="<?php echo base_url."image/chicken-border.svg"; ?>" alt="Logo Chicken" class="avatar"><!-- img.avatar -->

			<h2>Login Chicken</h2>

			<?php

				// Membuat pengaturan login

				// Jika button login tidak di klik
				if (!isset($_POST["submitLogin"])) {

					$valueUsername	=	"";
					$valuePassword 	=	"";

				} # if !$_POST["submitLogin"]

				// Jika button login di klik
				else {

					// Mengambil nilai pada setiap input data dari form login
					$valueUsername	=	$_POST["inputUsername"];
					$valuePassword 	=	$_POST["inputPassword"];

					// membuat pesan untuk peringatan jika ada kesalahan penginputan data login
					$pesan 			=	array();

					// Jika nilai input username kosong
					if (empty($valueUsername)) {
						$pesan[]	=	"Username tidak boleh kosong";
					} # if empty($valueUsername)

					// Jika nilai input password kosong
					if (empty($valuePassword)) {
						$pesan[]	=	"Password tidak boleh kosong";
					} # if empty($valuePassword)

					// Mengecek ada jika ada terjadi adanya kesalahan penginputan data
					if (count($pesan)>=1) {

						echo "<div class='alert'>";

							// mengatur no pesan error dari nol
							$no_pesan = 0;

							// menampilkan seluruh pesan kesalahan inputan data
							foreach ($pesan as $peringatan) {

								// membuat no pesan bertambah secara otomatis
								$no_pesan++;

								// menampilkan no pesan dan disejajarkan dengan pesan peringatan
								echo "$no_pesan. $peringatan <br>";

							} # foreach $pesan as $peringatan

						echo "</div>";

					} # if count $pesan

					// Jika tidak ada kesalahan penginputan data
					else {

						// memfilter inputan data login dengan mysqli_real_escape_string
						$valueUsername	=	mysqli_real_escape_string($koneksi,$valueUsername);
						$valuePassword	=	mysqli_real_escape_string($koneksi,$valuePassword);

						// mengenskripsi seluruh karakter inputan password
						$sha1			=	sha1($valuePassword);

						// mencari data login yang sesuai dengan inputan pada form
						$sql			=	mysqli_query($koneksi,	"
																		SELECT * FROM
																			tabel_admin
																		WHERE
																			kolom_username='$valueUsername'
																		AND
																			kolom_password_sha1='$sha1'
																		AND
																			kolom_status_admin='on'
																	")

																	or die ("
																		<div class='alert'>
																			sql error<br>
																			".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
																		<div>
																	");

						// mencocokan data
						$row 			=	mysqli_fetch_array($sql);

						// jika kecocokan data akurat
						if ($row==true) {

							// membuat sesi untuk admin yang sudah login dan menyimpannya pada session cookie
							$_SESSION["session_kode_admin"]		=	$row["kolom_kode_admin"];
							$_SESSION["session_username"]		=	$row["kolom_username"];
							$_SESSION["session_level"]			=	$row["kolom_level"];
							$_SESSION["session_status_admin"]	=	$row["kolom_status_admin"];

							echo "<script>";
							echo "window.location.replace('".base_url."')";
							echo "</script>";

						} # if $row true

						// namun jika kecocokan data belum tepat
						else {

							echo "<div class='alert'>";
							echo "Login Gagal";
							echo "</div>";

						} # else $row false

					} # else count $pesan

				} # else !$_POST["submitLogin"]

			?>

			<div class="container">

				<input type="text" name="inputUsername" value="<?php echo $valueUsername; ?>" placeholder="Username"><!-- input[type=text] -->

				<input type="password" name="inputPassword" value="<?php echo $valuePassword; ?>" placeholder="Password"><!-- input[type=password] -->

				<button type="submit" name="submitLogin">Login</button><!-- button -->

			</div><!-- .container -->

		</div><!-- .imgcontainer -->

	</form><!-- form -->

</body>

</html>

<?php

	mysqli_close($koneksi);
	ob_end_flush();

?>