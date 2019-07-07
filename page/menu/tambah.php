<div class="header">
			
	<h1>Menu</h1><!-- .main .header h1 -->

</div><!-- .main .header -->

<?php

/***	Mencari kode otomatis untuk menu 	***/

	// Mencocokan data pada tabel menu
	$sql1	=	mysqli_query($koneksi,	"
											SELECT
												kolom_kode_menu
											FROM
												tabel_menu
											ORDER BY
												kolom_kode_menu
											DESC LIMIT 1
										")

										or die("
											<div class='alert'>
												sql1 error<br>
												".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
											</div>
										");

	// jika didalam tabel_menu belum ada data maka kita akan membuat kode awal untuk menu adalah MN00
	if (mysqli_num_rows($sql1)==0) {

		$kode_terakhir	=	"MN00";

	} # if mysqli_num_rows $sql1 == 0
	
	// namun jika sudah ada data kita akan mencari data terakhir dan menyeleksi kode terkahir yang terletak pada kolom_kode_menu

	else {

		while ($row1 = mysqli_fetch_array($sql1)) {

			$kode_terakhir = $row1["kolom_kode_menu"];

		}

	} # else mysqli_num_rows $sql1 == 0

	// membuat sekaligus memanggil fungsi kode_otomatis
	$kodeMenu 	=	kode_otomatis($kode_terakhir, 'MN', 2);

/*** Proses penambahan data menu ***/

	// Jika tombol simpan yang bernama submitMenu tidak di klik
	if (!isset($_POST["submitMenu"])) {

		$valueNama			=	"";
		$valueHarga			=	"";
		$valueIsi			=	"";
		$valueKategori		=	"";
		$valueStatus 		=	"";

	} # if !isset $_POST["submitMenu"]

	// Jika tombol simpan yang bernama submitMenu di klik
	else {

		$valueNama			=	$_POST["inputNama"];
		$valueHarga			=	$_POST["inputHarga"];
		$valueIsi 			=	$_POST["inputIsi"];
		$valueKategori 		=	$_POST["inputKategori"];
		$valueStatus 		=	$_POST["inputStatus"];

		// membuat pesan untuk keterangan error menjadi array
		$pesan	=	array();

		// mengecek nama menu yang sama
		$sql2	=	mysqli_query($koneksi,	"
												SELECT * FROM
													tabel_menu
												WHERE
													kolom_nama_menu='$valueNama'
											")

											or die("
												<div class='alert'>
													sql2 error<br>
													".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
												</div>
											");

		// Jika ada nama yang sama saat penginputan nama menu maka kita memberika pesan
		if (mysqli_num_rows($sql2)>=1) {
			$pesan[] = "Nama ".$valueNama." sudah ada didalam data menu";
		}
		else if (empty($valueNama)) {
			$pesan[] = "Nama Menu tidak boleh kosong";
		}

		if (empty($valueIsi)) {
			$pesan[] = "Isi Menu tidak boleh kosong";
		}

		if (empty($valueHarga)) {
			$pesan[] = "Harga menu tidak boleh kosong";
		}

		if (empty($valueKategori)) {
			$pesan[] = "Kategori harus dipilih";
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
														tabel_menu(
															kolom_kode_menu,
															kolom_kode_kategori,
															kolom_nama_menu,
															kolom_isi_menu,
															kolom_harga_menu,
															kolom_status_menu
														)
													VALUES (
														'$kodeMenu',
														'$valueKategori',
														'$valueNama',
														'$valueIsi',
														'$valueHarga',
														'$valueStatus'
													)
												");

			// jika penginputan data benar
			if ($sql3==true) {

				echo 	"<script>";
				echo 	"
							if (confirm('Berhasil menambahkan data menu')) {
								window.location.replace('".base_url."?folder=menu&file=index');
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

	} # else isset $_POST["submitMenu"]

?>

<div class="card">

	<div class="card-body">
				
		<h3 class="card-subtitle">Tambah Menu</h3><!-- .card .card-body .card-subtitle -->

		<form method="post" class="form" name="formTambahMenu">
					
			<label>Kode Menu</label><!-- .card .form label -->
			<input type="text" disabled value="<?php echo $kodeMenu; ?>"><!-- .card .form input[type=text] -->
					
			<label>Nama Menu</label><!-- .card .form label -->
			<input type="text" name="inputNama" value="<?php echo $valueNama; ?>" placeholder="Nama Menu"><!-- .card .form- input[type=text] -->

			<label>Isi Menu</label><!-- .card .form label -->
			<input type="text" name="inputIsi" value="<?php echo $valueIsi; ?>" placeholder="Isi Menu"><!-- .card .form- input[type=text] -->

			<label>Harga Menu</label><!-- .card .form label -->
			<input type="text" name="inputHarga" value="<?php echo $valueHarga; ?>" placeholder="Harga Menu"><!-- .card .form- input[type=text] -->

			<?php

				// menampilkan data kategori yang berstatus on pada select kategori
				$sql4	=	mysqli_query($koneksi,	"
														SELECT * FROM
															tabel_kategori
														WHERE
															kolom_status_kategori='on'
													")

													or die("
														<div class='alert-form'>
															sql4 error<br>
															".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
														</div>
													");

			?>

			<label>Kategori</label><!-- .card .form label -->
			<select name="inputKategori">

				<option value="" <?php if ($valueKategori=="") echo "selected"; ?>>Pilih Kategori</option>

				<?php while ($row4 = mysqli_fetch_array($sql4)) { ?>

					<?php if ($row4["kolom_kode_kategori"]==$valueKategori) { ?>

						<option value="<?php echo $row4["kolom_kode_kategori"]; ?>" selected>
							<?php echo $row4["kolom_nama_kategori"]; ?>
						</option>

					<?php } else { ?>

						<option value="<?php echo $row4["kolom_kode_kategori"]; ?>">
							<?php echo $row4["kolom_nama_kategori"]; ?>
						</option>

					<?php } ?>

				<?php } ?>

			</select><!-- .card .form select -->

			<label>Status</label><!-- .card .form label -->
			<select name="inputStatus">
				<option value="" <?php if ($valueStatus=="") echo "selected"; ?>>Pilih Status</option>
				<option value="on" <?php if ($valueStatus=="on") echo "selected"; ?>>On</option>
				<option value="off" <?php if ($valueStatus=="off") echo "selected"; ?>>Off</option>
			</select><!-- .card .form select -->

			<button type="submit" name="submitMenu">Simpan</button><!-- .card .form button -->

		</form>

	</div><!-- .card .card-body -->

</div><!-- .card -->