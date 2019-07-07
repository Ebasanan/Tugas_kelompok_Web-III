<div class="header">
			
	<h1>Admin</h1><!-- .main .header h1 -->

</div><!-- .main .header -->

<?php

/***	Mencari kode otomatis untuk admin 	***/

	// Mencocokan data pada tabel admin
	$sql1	=	mysqli_query($koneksi,	"
											SELECT
												kolom_kode_admin
											FROM
												tabel_admin
											ORDER BY
												kolom_kode_admin
											DESC LIMIT 1
										")

										or die("
											<div class='alert'>
												sql1 error<br>
												".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
											</div>
										");

	// jika didalam tabel_admin belum ada data maka kita akan membuat kode awal untuk admin adalah AD00
	if (mysqli_num_rows($sql1)==0) {

		$kode_terakhir	=	"AD00";

	} # if mysqli_num_rows $sql1 == 0

	// namun jika sudah ada data kita akan mencari data terakhir dan menyeleksi kode terkahir yang terletak pada kolom_kode_admin
	else {

		while ($row1 = mysqli_fetch_array($sql1)) {

			$kode_terakhir = $row1["kolom_kode_admin"];

		}

	} # else mysqli_num_rows $sql1 == 0

	// membuat sekaligus memanggil fungsi kode_otomatis
	$kodeAdmin 	=	kode_otomatis($kode_terakhir, 'AD', 2);

/*** Proses penambahan data admin ***/

	// Jika tombol simpan yang bernama submitAmin tidak di klik
	if (!isset($_POST["submitAdmin"])) {

		$valueUsername	=	"";
		$valuePassword	=	"";
		$valueLevel 	=	"";
		$valueStatus 	=	"";

	} # if !isset $_POST["submitAdmin"]

	// Jika tombol simpan yang bernama submitAdmin di klik
	else {

		$valueUsername	=	$_POST["inputUsername"];
		$valuePassword	=	$_POST["inputPassword"];
		$valueLevel 	=	$_POST["inputLevel"];
		$valueStatus 	=	$_POST["inputStatus"];

		// membuat pesan untuk keterangan error menjadi array
		$pesan	=	array();

		// mengecek username yang sama
		$sql2	=	mysqli_query($koneksi,	"
												SELECT * FROM
													tabel_admin
												WHERE
													kolom_username='$valueUsername'
											")

											or die("
												<div class='alert'>
													sql2 error<br>
													".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
												</div>
											");

		// Jika ada nama yang sama saat penginputan username maka kita memberika pesan
		if (mysqli_num_rows($sql2)>=1) {
			$pesan[] = "Username ".$valueUsername." sudah ada didalam data admin";
		}
		else if (empty($valueUsername)) {
			$pesan[] = "Username tidak boleh kosong";
		}

		if (empty($valuePassword)) {
			$pesan[] = "Password tidak boleh kosong";
		}

		if (empty($valueLevel)) {
			$pesan[] = "Level harus dipilih";
		}

		if (empty($valueStatus)) {
			$pesan[] = "Status harus dipilih";
		}

		if (count($pesan)>=1) {

			echo "<div class='alert'>";

			// mengatur no pesan error mulai dari 0
			$no_pesan = 0;

			// mengulang semua kesalahan yang ada
			foreach ($pesan as $peringatan) {

				// membuat no pesan menjadi bertambah secara otomatis
				$no_pesan++;

				echo "$no_pesan. $peringatan<br>";

			} # foreach $pesan as $peringatan

			echo "</div>";

		} # if count $pesan >=1

		else {

			$sha1	=	sha1($valuePassword);

			$sql3 	=	mysqli_query($koneksi,	"
													INSERT INTO
														tabel_admin(
															kolom_kode_admin,
															kolom_username,
															kolom_password,
															kolom_password_sha1,
															kolom_level,
															kolom_status_admin
														)
													VALUES (
														'$kodeAdmin',
														'$valueUsername',
														'$valuePassword',
														'$sha1',
														'$valueLevel',
														'$valueStatus'
													)
												");

			// jika penginputan data benar
			if ($sql3==true) {

				echo 	"<script>";
				echo 	"
							if (confirm('Berhasil menambahkan data admin')) {
								window.location.replace('".base_url."?folder=admin&file=index');
							}
						";
				echo 	"</script>";

			} # if $sql3==true

			// jika penginputan data salah
			else {

				echo 	"<div class='alert'>";
				echo 	"sql3 error<br>";
				echo 	"".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."";
				echo 	"</div>";

			} # else $sql3==false

		}  # else count $pesan ==0

	} # else isset $_POST["submitAdmin"]

?>

<div class="card">

	<div class="card-body">
				
		<h3 class="card-subtitle">Tambah Admin</h3><!-- .card .card-body .card-subtitle -->

		<form method="post" class="form" name="formTambahAdmin">
					
			<label>Kode Admin</label><!-- .card .form label -->
			<input type="text" disabled value="<?php echo $kodeAdmin; ?>"><!-- .card .form input[type=text] -->
					
			<label>Username</label><!-- .card .form label -->
			<input type="text" name="inputUsername" value="<?php echo $valueUsername; ?>" placeholder="Username"><!-- .card .form- input[type=text] -->

			<label>Password</label><!-- .card .form label -->
			<input type="password" name="inputPassword" value="<?php echo $valuePassword; ?>" placeholder="Password"><!-- .card .form input[type=password] -->

			<label>Level</label><!-- .card .form label -->
			<select name="inputLevel">
				<option value="" <?php if ($valueLevel=="") echo "selected"; ?>>Pilih Level</option>
				<option value="admin" <?php if ($valueLevel=="admin") echo "selected"; ?>>Admin</option>
				<option value="kasir" <?php if ($valueLevel=="kasir") echo "selected"; ?>>Kasir</option>
			</select><!-- .card .form select -->

			<label>Status</label><!-- .card .form label -->
			<select name="inputStatus">
				<option value="" <?php if ($valueStatus=="") echo "selected"; ?>>Pilih Status</option>
				<option value="on" <?php if ($valueStatus=="on") echo "selected"; ?>>On</option>
				<option value="off" <?php if ($valueStatus=="off") echo "selected"; ?>>Off</option>
			</select><!-- .card .form select -->

			<button type="submit" name="submitAdmin">Simpan</button><!-- .card .form button -->

		</form>

	</div><!-- .card .card-body -->

</div><!-- .card -->