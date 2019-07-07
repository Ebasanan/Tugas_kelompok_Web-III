<div class="header">
			
	<h1>Agen</h1><!-- .main .header h1 -->

</div><!-- .main .header -->

<?php

	// mengecek nilai kode_agen data pada url
	$kode_agen	= isset($_GET["kode_agen"]) ? $_GET["kode_agen"] : false;

/***	Mencari data yang sama berdasarkan nilai dari $kode_agen 	***/

	// Mencocokan data pada tabel agen
	$sql1	=	mysqli_query($koneksi,	"
											SELECT * FROM
												tabel_agen
											WHERE
												kolom_kode_agen='$kode_agen'
										")

										or die("
											<div class='alert'>
												sql1 error<br>
												".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
											</div>
										");

	// mengeluarkan data agen
	$row1 	=	mysqli_fetch_array($sql1);

	// Jika tombol simpan yang bernama submitAgen tidak di klik
	if (!isset($_POST["submitAgen"])) {

		$valueKode			=	$row1["kolom_kode_agen"];
		$valueCek			=	$row1["kolom_nama_agen"];
		$valueNama			=	$row1["kolom_nama_agen"];
		$valueTelepon		=	$row1["kolom_telepon_agen"];
		$valueDistributor 	=	$row1["kolom_distributor"];
		$valueStatus 		=	$row1["kolom_status_agen"];

	} # if !isset $_POST["submitAgen"]

	// Jika tombol simpan yang bernama submitAgen di klik
	else {

		$valueKode			=	$_POST["inputKode"];
		$valueCek			=	$_POST["inputCek"];
		$valueNama			=	$_POST["inputNama"];
		$valueTelepon		=	$_POST["inputTelepon"];
		$valueDistributor 	=	$_POST["inputDistributor"];
		$valueStatus 		=	$_POST["inputStatus"];

		// membuat pesan untuk keterangan error menjadi array
		$pesan	=	array();

		// mengecek nama agen yang sama
		$sql2	=	mysqli_query($koneksi,	"
												SELECT * FROM
													tabel_agen
												WHERE
													kolom_nama_agen='$valueNama'
												AND NOT (
													kolom_nama_agen='$valueCek'
												)
											")

											or die("
												<div class='alert'>
													sql2 error<br>
													".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
												</div>
											");

		// Jika ada nama yang sama saat penginputan nama agen maka kita memberika pesan
		if (mysqli_num_rows($sql2)>=1) {
			$pesan[] = "Nama ".$valueNama." sudah ada didalam data agen";
		}
		else if (empty($valueNama)) {
			$pesan[] = "Nama Agen tidak boleh kosong";
		}

		if (empty($valueTelepon)) {
			$pesan[] = "Telepon tidak boleh kosong";
		}

		if (empty($valueDistributor)) {
			$pesan[] = "Distributor tidak boleh kosong";
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

			$sql3 	=	mysqli_query($koneksi,	"
													UPDATE
														tabel_agen
													SET
														kolom_nama_agen='$valueNama',
														kolom_telepon_agen='$valueTelepon',
														kolom_distributor='$valueDistributor',
														kolom_status_agen='$valueStatus'
													WHERE
														kolom_kode_agen='$valueKode'
												");

			// jika penginputan data benar
			if ($sql3==true) {

				echo 	"<script>";
				echo 	"
							if (confirm('Berhasil mengubah data agen')) {
								window.location.replace('".base_url."?folder=agen&file=index');
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

	} # else isset $_POST["submitAgen"]

?>

<?php if (!$valueKode) { ?>

	<div class="alert">
		Tidak ditemukan data agen
	</div><!-- .alert -->

<?php } else { ?>

	<div class="card">

		<div class="card-body">
					
			<h3 class="card-subtitle">Ubah Agen</h3><!-- .card .card-body .card-subtitle -->

			<form method="post" class="form" name="formUbahAgen">

				<input type="hidden" name="inputKode" value="<?php echo $valueKode; ?>">
				<input type="hidden" name="inputCek" value="<?php echo $valueCek; ?>">

				<label>Kode Agen</label><!-- .card .form label -->
				<input type="text" disabled value="<?php echo $valueKode; ?>"><!-- .card .form input[type=text] -->
						
				<label>Nama Agen</label><!-- .card .form label -->
				<input type="text" name="inputNama" value="<?php echo $valueNama; ?>" placeholder="Nama Agen"><!-- .card .form- input[type=text] -->

				<label>No Telepon</label><!-- .card .form label -->
				<input type="text" name="inputTelepon" value="<?php echo $valueTelepon; ?>" placeholder="No Telepon"><!-- .card .form input[type=text] -->

				<label>Distributor</label><!-- .card .form label -->
				<input type="text" name="inputDistributor" value="<?php echo $valueDistributor; ?>" placeholder="Distributor"><!-- .card .form input[type=text] -->

				<label>Status</label><!-- .card .form label -->
				<select name="inputStatus">
					<option value="" <?php if ($valueStatus=="") echo "selected"; ?>>Pilih Status</option>
					<option value="on" <?php if ($valueStatus=="on") echo "selected"; ?>>On</option>
					<option value="off" <?php if ($valueStatus=="off") echo "selected"; ?>>Off</option>
				</select><!-- .card .form select -->

				<button type="submit" name="submitAgen">Simpan</button><!-- .card .form button -->

			</form>

		</div><!-- .card .card-body -->

	</div><!-- .card -->

<?php } ?>