<div class="header">
			
	<h1>Penjualan</h1><!-- .main .header h1 -->

</div><!-- .main .header -->

<?php

	// mengecek nilai kode_item_menu data pada url
	$kode_item_menu	= isset($_GET["kode_item_menu"]) ? $_GET["kode_item_menu"] : false;

/***	Mencari data yang sama berdasarkan nilai dari $kode_item_menu 	***/

	// Mencocokan data pada tabel item menu
	$sql1	=	mysqli_query($koneksi,	"
											SELECT * FROM
												tabel_item_menu
											WHERE
												kolom_kode_item_menu='$kode_item_menu'
											AND
												kolom_kode_penjualan=''
										")

										or die("
											<div class='alert'>
												sql1 error<br>
												".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
											</div>
										");

	// mengeluarkan tabel item menu
	$row1 	=	mysqli_fetch_array($sql1);

	// Jika tombol simpan yang bernama submitItemMenu tidak di klik
	if (!isset($_POST["submitItemMenu"])) {

		$valueKode		=	$row1["kolom_kode_item_menu"];
		$valueAdmin	 	=	$row1["kolom_kode_admin"];
		$valueMenu		=	$row1["kolom_kode_menu"];
		$valueJumlah	=	$row1["kolom_jumlah_porsi"];

	} # if !isset $_POST["submitItemMenu"]

	// Jika tombol simpan yang bernama submitItemMenu di klik
	else {

		$valueKode		=	$_POST["inputKode"];
		$valueAdmin	 	=	$_POST["inputAdmin"];
		$valueMenu		=	$_POST["inputMenu"];
		$valueJumlah	=	$_POST["inputJumlah"];

		// membuat pesan untuk keterangan error menjadi array
		$pesan	=	array();

		if (empty($valueMenu)) {
			$pesan[] = "Menu harus dipilih";
		}

		if (empty($valueJumlah)) {
			$pesan[] = "Jumlah Porsi tidak boleh kosong";
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
														tabel_item_menu
													SET		
														kolom_kode_penjualan='',
														kolom_kode_admin='$valueAdmin',
														kolom_kode_menu='$valueMenu',
														kolom_jumlah_porsi='$valueJumlah'
													WHERE
														kolom_kode_item_menu='$valueKode'
												");

			// jika penginputan data benar
			if ($sql3==true) {

				echo 	"<script>";
				echo 	"
							if (confirm('Berhasil mengubah data item menu')) {
								window.location.replace('".base_url."?folder=penjualan&file=tambah');
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

	} # else isset $_POST["submitItemMenu"]

?>

<div class="card">

	<div class="card-body">
				
		<h3 class="card-subtitle">Ubah Item Menu</h3><!-- .card .card-body .card-subtitle -->

		<form method="post" class="form" name="formUbahItemMenu">

			<input type="hidden" name="inputKode" value="<?php echo $valueKode; ?>">
			<input type="hidden" name="inputAdmin" value="<?php echo $valueAdmin; ?>">

			<?php

				// menampilkan data menu yang berstatus on pada select menu
				$sql2	=	mysqli_query($koneksi,	"
														SELECT * FROM
															tabel_menu
														WHERE
															kolom_status_menu='on'
													")

													or die("
														<div class='alert-form'>
															sql2 error<br>
															".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
														</div>
													");

			?>

			<label>Menu</label><!-- .card .form label -->
			<select name="inputMenu">

				<option value="" <?php if ($valueMenu=="") echo "selected"; ?>>Pilih Menu</option>

				<?php while ($row2 = mysqli_fetch_array($sql2)) { ?>

					<?php if ($row2["kolom_kode_menu"]==$valueMenu) { ?>

						<option value="<?php echo $row2["kolom_kode_menu"]; ?>" selected>
							<?php echo $row2["kolom_nama_menu"]; ?>
						</option>

					<?php } else { ?>

						<option value="<?php echo $row2["kolom_kode_menu"]; ?>">
							<?php echo $row2["kolom_nama_menu"]; ?>
						</option>

					<?php } ?>

				<?php } ?>

			</select><!-- .card .form select -->

			<label>Jumlah Porsi</label><!-- .card .form label -->
			<input type="text" name="inputJumlah" value="<?php echo $valueJumlah; ?>" placeholder="Jumlah Porsi"><!-- .card .form- input[type=text] -->

			<button type="submit" name="submitItemMenu">Simpan</button><!-- .card .form button -->

		</form>

	</div><!-- .card .card-body -->

</div><!-- .card -->