<div class="header">
			
	<h1>Pembelian</h1><!-- .main .header h1 -->

</div><!-- .main .header -->

<?php

	// mengecek nilai kode_item_barang data pada url
	$kode_item_barang	= isset($_GET["kode_item_barang"]) ? $_GET["kode_item_barang"] : false;

/***	Mencari data yang sama berdasarkan nilai dari $kode_item_barang 	***/

	// Mencocokan data pada tabel item barang
	$sql1	=	mysqli_query($koneksi,	"
											SELECT * FROM
												tabel_item_barang
											WHERE
												kolom_kode_item_barang='$kode_item_barang'
											AND
												kolom_kode_pembelian=''
										")

										or die("
											<div class='alert'>
												sql1 error<br>
												".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
											</div>
										");

	// mengeluarkan tabel item barang
	$row1 	=	mysqli_fetch_array($sql1);

	// Jika tombol simpan yang bernama submitItemBarang tidak di klik
	if (!isset($_POST["submitItemBarang"])) {

		$valueKode		=	$row1["kolom_kode_item_barang"];
		$valueAdmin	 	=	$row1["kolom_kode_admin"];
		$valueAgen		=	$row1["kolom_kode_agen"];
		$valueBarang	=	$row1["kolom_nama_barang"];
		$valueHarga		=	$row1["kolom_harga_barang"];
		$valueJumlah	=	$row1["kolom_jumlah_barang"];
		$valueSatuan 	=	$row1["kolom_kode_satuan"];

	} # if !isset $_POST["submitItemBarang"]

	// Jika tombol simpan yang bernama submitItemBarang di klik
	else {

		$valueKode		=	$_POST["inputKode"];
		$valueAdmin	 	=	$_POST["inputAdmin"];
		$valueAgen		=	$_POST["inputAgen"];
		$valueBarang	=	$_POST["inputBarang"];
		$valueHarga		=	$_POST["inputHarga"];
		$valueJumlah	=	$_POST["inputJumlah"];
		$valueSatuan	=	$_POST["inputSatuan"];

		// membuat pesan untuk keterangan error menjadi array
		$pesan	=	array();

		if (empty($valueAgen)) {
			$pesan[] = "Agen harus dipilih";
		}

		if (empty($valueBarang)) {
			$pesan[] = "Nama Barang tidak boleh kosong";
		}

		if (empty($valueHarga)) {
			$pesan[] = "Harga Barang tidak boleh kosong";
		}

		if (empty($valueJumlah)) {
			$pesan[] = "Jumlah Barang tidak boleh kosong";
		}

		if (empty($valueSatuan)) {
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

			$sql4 	=	mysqli_query($koneksi,	"
													UPDATE
														tabel_item_barang
													SET
														kolom_kode_pembelian='',
														kolom_kode_admin='$valueAdmin',
														kolom_kode_agen='$valueAgen',
														kolom_kode_satuan='$valueSatuan',
														kolom_nama_barang='$valueBarang',
														kolom_harga_barang='$valueHarga',
														kolom_jumlah_barang='$valueJumlah'
													WHERE
														kolom_kode_item_barang='$valueKode'
												");

			// jika penginputan data benar
			if ($sql4==true) {

				echo 	"<script>";
				echo 	"
							if (confirm('Berhasil mengubah data item barang')) {
								window.location.replace('".base_url."?folder=pembelian&file=tambah');
							}
						";
				echo 	"</script>";

			} # if $sql4==true

			// jika penginputan data salah
			else {

				echo 	"<div class='alert'>";
				echo 	"sql4 error<br>";
				echo 	"".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."";
				echo 	"</div>";

			} # else $sql4==false

		}  # else count $pesan ==0

	} # else isset $_POST["submitItemBarang"]

?>

<?php if (!$valueKode) { ?>

	<div class="alert">
		Tidak ditemukan data item barang
	</div><!-- .alert -->

<?php } else { ?>

	<div class="card">

		<div class="card-body">
					
			<h3 class="card-subtitle">Ubah Item Barang</h3><!-- .card .card-body .card-subtitle -->

			<form method="post" class="form" name="formUbahItemBarang">

				<input type="hidden" name="inputKode" value="<?php echo $valueKode; ?>">
				<input type="hidden" name="inputAdmin" value="<?php echo $valueAdmin; ?>">

				<?php

					// menampilkan data agen yang berstatus on pada select agen
					$sql2	=	mysqli_query($koneksi,	"
															SELECT * FROM
																tabel_agen
															WHERE
																kolom_status_agen='on'
														")

														or die("
															<div class='alert-form'>
																sql2 error<br>
																".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
															</div>
														");

				?>

				<label>Agen</label><!-- .card .form label -->
				<select name="inputAgen">

					<option value="" <?php if ($valueAgen=="") echo "selected"; ?>>Pilih Agen</option>

					<?php while ($row2 = mysqli_fetch_array($sql2)) { ?>

						<?php if ($row2["kolom_kode_agen"]==$valueAgen) { ?>

							<option value="<?php echo $row2["kolom_kode_agen"]; ?>" selected>
								<?php echo $row2["kolom_nama_agen"]." - ".$row2["kolom_distributor"]; ?>
							</option>

						<?php } else { ?>

							<option value="<?php echo $row2["kolom_kode_agen"]; ?>">
								<?php echo $row2["kolom_nama_agen"]." - ".$row2["kolom_distributor"]; ?>
							</option>

						<?php } ?>

					<?php } ?>

				</select><!-- .card .form select -->
						
				<label>Nama Item Barang</label><!-- .card .form label -->
				<input type="text" name="inputBarang" value="<?php echo $valueBarang; ?>" placeholder="Nama Item Barang"><!-- .card .form- input[type=text] -->

				<label>Harga Satuan Barang</label><!-- .card .form label -->
				<input type="text" name="inputHarga" value="<?php echo $valueHarga; ?>" placeholder="Harga Satuan Barang"><!-- .card .form input[type=text] -->

				<label>Jumlah Barang</label><!-- .card .form label -->
				<input type="text" name="inputJumlah" value="<?php echo $valueJumlah; ?>" placeholder="Jumlah Barang"><!-- .card .form input[type=text] -->

				<?php

					// menampilkan data satuan yang berstatus on pada select satuan
					$sql3	=	mysqli_query($koneksi,	"
															SELECT * FROM
																tabel_satuan
															WHERE
																kolom_status_satuan='on'
														")

														or die("
															<div class='alert-form'>
																sql3 error<br>
																".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
															</div>
														");

				?>

				<label>Satuan</label><!-- .card .form label -->
				<select name="inputSatuan">

					<option value="" <?php if ($valueSatuan=="") echo "selected"; ?>>Pilih Satuan</option>

					<?php while ($row3 = mysqli_fetch_array($sql3)) { ?>

						<?php if ($row3["kolom_kode_satuan"]==$valueSatuan) { ?>

							<option value="<?php echo $row3["kolom_kode_satuan"]; ?>" selected>
								<?php echo $row3["kolom_nama_satuan"]; ?>
							</option>

						<?php } else { ?>

							<option value="<?php echo $row3["kolom_kode_satuan"]; ?>">
								<?php echo $row3["kolom_nama_satuan"]; ?>
							</option>

						<?php } ?>

					<?php } ?>

				</select><!-- .card .form select -->

				<button type="submit" name="submitItemBarang">Simpan</button><!-- .card .form button -->

			</form>

		</div><!-- .card .card-body -->

	</div><!-- .card -->

<?php } ?>