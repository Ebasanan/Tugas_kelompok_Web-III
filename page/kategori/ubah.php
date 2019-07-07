<div class="header">
			
	<h1>Kategori</h1><!-- .main .header h1 -->

</div><!-- .main .header -->

<?php

	// mengecek nilai kode_kategori data pada url
	$kode_kategori	= isset($_GET["kode_kategori"]) ? $_GET["kode_kategori"] : false;

/***	Mencari data yang sama berdasarkan nilai dari $kode_kategori 	***/

	// Mencocokan data pada tabel kategori
	$sql1	=	mysqli_query($koneksi,	"
											SELECT * FROM
												tabel_kategori
											WHERE
												kolom_kode_kategori='$kode_kategori'
										")

										or die("
											<div class='alert'>
												sql1 error<br>
												".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
											</div>
										");

	// mengeluarkan data kategori
	$row1 	=	mysqli_fetch_array($sql1);

	// Jika tombol simpan yang bernama submitKategori tidak di klik
	if (!isset($_POST["submitKategori"])) {

		$valueKode			=	$row1["kolom_kode_kategori"];
		$valueCek			=	$row1["kolom_nama_kategori"];
		$valueNama			=	$row1["kolom_nama_kategori"];
		$valueStatus 		=	$row1["kolom_status_kategori"];

	} # if !isset $_POST["submitKategori"]

	// Jika tombol simpan yang bernama submitKategori di klik
	else {

		$valueKode			=	$_POST["inputKode"];
		$valueCek			=	$_POST["inputCek"];
		$valueNama			=	$_POST["inputNama"];
		$valueStatus 		=	$_POST["inputStatus"];

		// membuat pesan untuk keterangan error menjadi array
		$pesan	=	array();

		// mengecek nama kategori yang sama
		$sql2	=	mysqli_query($koneksi,	"
												SELECT * FROM
													tabel_kategori
												WHERE
													kolom_nama_kategori='$valueNama'
												AND NOT (
													kolom_nama_kategori='$valueCek'
												)
											")

											or die("
												<div class='alert'>
													sql2 error<br>
													".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
												</div>
											");

		// Jika ada nama yang sama saat penginputan nama kategori maka kita memberika pesan
		if (mysqli_num_rows($sql2)>=1) {
			$pesan[] = "Nama ".$valueNama." sudah ada didalam data kategori";
		}
		else if (empty($valueNama)) {
			$pesan[] = "Nama kategori tidak boleh kosong";
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
														tabel_kategori
													SET
														kolom_nama_kategori='$valueNama',
														kolom_status_kategori='$valueStatus'
													WHERE
														kolom_kode_kategori='$valueKode'
												");

			// jika penginputan data benar
			if ($sql3==true) {

				echo 	"<script>";
				echo 	"
							if (confirm('Berhasil mengubah data kategori')) {
								window.location.replace('".base_url."?folder=kategori&file=index');
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

	} # else isset $_POST["submitKategori"]

?>

<?php if (!$valueKode) { ?>

	<div class="alert">
		Tidak ditemukan data kategori
	</div><!-- .alert -->

<?php } else { ?>

	<div class="card">

		<div class="card-body">
					
			<h3 class="card-subtitle">Ubah Kategori</h3><!-- .card .card-body .card-subtitle -->

			<form method="post" class="form" name="formUbahKategori">

				<input type="hidden" name="inputKode" value="<?php echo $valueKode; ?>">
				<input type="hidden" name="inputCek" value="<?php echo $valueCek; ?>">

				<label>Kode Kategori</label><!-- .card .form label -->
				<input type="text" disabled value="<?php echo $valueKode; ?>"><!-- .card .form input[type=text] -->
						
				<label>Nama Kategori</label><!-- .card .form label -->
				<input type="text" name="inputNama" value="<?php echo $valueNama; ?>" placeholder="Nama Kategori"><!-- .card .form- input[type=text] -->

				<label>Status</label><!-- .card .form label -->
				<select name="inputStatus">
					<option value="" <?php if ($valueStatus=="") echo "selected"; ?>>Pilih Status</option>
					<option value="on" <?php if ($valueStatus=="on") echo "selected"; ?>>On</option>
					<option value="off" <?php if ($valueStatus=="off") echo "selected"; ?>>Off</option>
				</select><!-- .card .form select -->

				<button type="submit" name="submitKategori">Simpan</button><!-- .card .form button -->

			</form>

		</div><!-- .card .card-body -->

	</div><!-- .card -->

<?php } ?>