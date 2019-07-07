<div class="header">
			
	<h1>Satuan</h1><!-- .main .header h1 -->

</div><!-- .main .header -->

<?php

/***	Mencari kode otomatis untuk satuan 	***/

	// Mencocokan data pada tabel satuan
	$sql1	=	mysqli_query($koneksi,	"
											SELECT
												kolom_kode_satuan
											FROM
												tabel_satuan
											ORDER BY
												kolom_kode_satuan
											DESC LIMIT 1
										")

										or die("
											<div class='alert'>
												sql1 error<br>
												".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
											</div>
										");

	// jika didalam tabel_satuan belum ada data maka kita akan membuat kode awal untuk satuan adalah ST00
	if (mysqli_num_rows($sql1)==0) {

		$kode_terakhir	=	"ST00";

	} # if mysqli_num_rows $sql1 == 0

	// namun jika sudah ada data kita akan mencari data terakhir dan menyeleksi kode terkahir yang terletak pada kolom_kode_satuan
	else {

		while ($row1 = mysqli_fetch_array($sql1)) {

			$kode_terakhir = $row1["kolom_kode_satuan"];

		}

	} # else mysqli_num_rows $sql1 == 0

	// membuat sekaligus memanggil fungsi kode_otomatis
	$kodeSatuan 	=	kode_otomatis($kode_terakhir, 'ST', 2);

/*** Proses penambahan data agen ***/

	// Jika tombol simpan yang bernama submitSatuan tidak di klik
	if (!isset($_POST["submitSatuan"])) {

		$valueNama			=	"";
		$valueStatus 		=	"";

	} # if !isset $_POST["submitSatuan"]

	// Jika tombol simpan yang bernama submitSatuan di klik
	else {

		$valueNama			=	$_POST["inputNama"];
		$valueStatus 		=	$_POST["inputStatus"];

		// membuat pesan untuk keterangan error menjadi array
		$pesan	=	array();

		// mengecek nama satuan yang sama
		$sql2	=	mysqli_query($koneksi,	"
												SELECT * FROM
													tabel_satuan
												WHERE
													kolom_nama_satuan='$valueNama'
											")

											or die("
												<div class='alert'>
													sql2 error<br>
													".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
												</div>
											");

		// Jika ada nama yang sama saat penginputan nama satuan maka kita memberika pesan
		if (mysqli_num_rows($sql2)>=1) {
			$pesan[] = "Nama ".$valueNama." sudah ada didalam data satuan";
		}
		else if (empty($valueNama)) {
			$pesan[] = "Nama satuan tidak boleh kosong";
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
													INSERT INTO
														tabel_satuan(
															kolom_kode_satuan,
															kolom_nama_satuan,
															kolom_status_satuan
														)
													VALUES (
														'$kodeSatuan',
														'$valueNama',
														'$valueStatus'
													)
												");

			// jika penginputan data benar
			if ($sql3==true) {

				echo 	"<script>";
				echo 	"
							if (confirm('Berhasil menambahkan data satuan')) {
								window.location.replace('".base_url."?folder=satuan&file=index');
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

	} # else isset $_POST["submitSatuan"]


?>

<div class="card">

	<div class="card-body">
				
		<h3 class="card-subtitle">Tambah Satuan</h3><!-- .card .card-body .card-subtitle -->

		<form method="post" class="form" name="formTambahSatuan">
					
			<label>Kode Satuan</label><!-- .card .form label -->
			<input type="text" disabled value="<?php echo $kodeSatuan; ?>"><!-- .card .form input[type=text] -->
					
			<label>Nama Satuan</label><!-- .card .form label -->
			<input type="text" name="inputNama" value="<?php echo $valueNama; ?>" placeholder="Nama Satuan"><!-- .card .form- input[type=text] -->

			<label>Status</label><!-- .card .form label -->
			<select name="inputStatus">
				<option value="" <?php if ($valueStatus=="") echo "selected"; ?>>Pilih Status</option>
				<option value="on" <?php if ($valueStatus=="on") echo "selected"; ?>>On</option>
				<option value="off" <?php if ($valueStatus=="off") echo "selected"; ?>>Off</option>
			</select><!-- .card .form select -->

			<button type="submit" name="submitSatuan">Simpan</button><!-- .card .form button -->

		</form>

	</div><!-- .card .card-body -->

</div><!-- .card -->