<?php

	// mengatur zona waktu untuk Waktu Indonesia Barat
	date_default_timezone_set("Asia/Bangkok");

	// menampilkan tanggal sekarang
	$tanggal_sekarang = date('ymd');

	// nilai awal potongan tanggal adalah kosong atau tidak memiliki nilai apapun
	$potongan_tanggal = "";

?>

<div class="header">
			
	<h1>Pembelian</h1><!-- .main .header h1 -->

</div><!-- .main .header -->

<?php

/***	Mencari kode otomatis untuk item barang 	***/

	// Mencocokan data pada tabel item barang
	$sql1	=	mysqli_query($koneksi,	"
											SELECT
												kolom_kode_item_barang
											FROM
												tabel_item_barang
											ORDER BY
												kolom_kode_item_barang
											DESC LIMIT 1
										")

										or die("
											<div class='alert'>
												sql1 error<br>
												".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
											</div>
										");


	// jika didalam tabel_item_barang belum ada data maka kita akan membuat kode awal untuk item barang adalah IB0000000000000
	if (mysqli_num_rows($sql1)==0) {

		$kode_terakhir	=	"IB0000000000000";

	} # if mysqli_num_rows $sql1 == 0

	// namun jika sudah ada data kita akan mencari data terakhir dan menyeleksi kode terkahir yang terletak pada kolom_kode_item_barang
	else {

		while ($row1 = mysqli_fetch_array($sql1)) {

			$kode_terakhir = $row1["kolom_kode_item_barang"];

		}

		// memotong inisial tanggal terakhir yang berada didalam kolom_kode_item_barang
		$potongan_tanggal =	substr($kode_terakhir,2,6);

	} # else mysqli_num_rows $sql1 == 0

	// jika tanggal sekarang tidak sama dengan potongan tanggal yang dipilih dari kolom_kode_item_barang
	if ($tanggal_sekarang!==$potongan_tanggal) {

		$kodeItem 	= 	"IB".$tanggal_sekarang."0000001";
		
	} # if $tanggal_sekarang != $potongan_tanggal

	// jika tanggal sekarang sama dengan potongan tanggal yang dipilih dari kolom_kode_item_barang
	else {

		$kodeItem 	= 	kode_otomatis($kode_terakhir, 'IB'.$tanggal_sekarang, 7);

	} # else $tanggal_sekarang == $potongan_tanggal

/*** Proses penambahan data item barang ***/

	// Jika tombol pilih yang bernama submitPilih tidak di klik
	if (!isset($_POST["submitPilih"])) {

		$valueAgen		=	"";
		$valueBarang	=	"";
		$valueHarga		=	"";
		$valueJumlah	=	"";
		$valueSatuan 	=	"";

	} # if !isset $_POST["submitPilih"]

	// Jika tombol pilih yang bernama submitPilih di klik
	else {

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
													INSERT INTO
														tabel_item_barang(
															kolom_kode_item_barang,
															kolom_kode_pembelian,
															kolom_kode_admin,
															kolom_kode_agen,
															kolom_kode_satuan,
															kolom_nama_barang,
															kolom_harga_barang,
															kolom_jumlah_barang
														)
													VALUES (
														'$kodeItem',
														'',
														'$session_kode_admin',
														'$valueAgen',
														'$valueSatuan',
														'$valueBarang',
														'$valueHarga',
														'$valueJumlah'
													)
												");

			// jika penginputan data benar
			if ($sql4==true) {

				echo 	"<script>";
				echo 	"
							if (confirm('Berhasil menambahkan data item barang')) {
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

	} # else isset $_POST["submitPilih"]

?>

<div class="card">

	<div class="card-body">
				
		<h3 class="card-subtitle">Transaksi Pembelian Barang</h3><!-- .card .card-body .card-subtitle -->

		<form method="post" class="form" name="formItemBarang">

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

			<button type="submit" name="submitPilih">Pilih</button><!-- .card .form button -->

		</form>

	</div><!-- .card .card-body -->

</div><!-- .card -->

<?php

	// menampilkan data agen yang berstatus on pada select agen
	$sql5	=	mysqli_query($koneksi,	"
											SELECT
												tabel_item_barang.*,
												tabel_agen.*,
												tabel_satuan.*
											FROM
												tabel_item_barang
											INNER JOIN
												tabel_agen
											ON
												tabel_item_barang.kolom_kode_agen=tabel_agen.kolom_kode_agen
											INNER JOIN
												tabel_satuan
											ON
												tabel_item_barang.kolom_kode_satuan=tabel_satuan.kolom_kode_satuan
											WHERE
												tabel_item_barang.kolom_kode_admin='$session_kode_admin'
											AND
												tabel_item_barang.kolom_kode_pembelian=''
										")

										or die("
												<div class='alert'>
													sql5 error<br>
													".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
												</div>
										");

	// nilai awal dari biaya pembelian dan jumlah barang adalah 0
	$biaya_pembelian	=	0;
	$jumlah_barang 		=	0;

?>

<!-- Jika pengeluaran data item barang tidak dapat ditemukan atau tidak ada data sama sekali -->
<?php if (mysqli_num_rows($sql5)==0) { ?>

	<div class="alert">
		Kamu belum memiliki daftar item barang
	</div><!-- .alert -->

<!-- Jika pengeluaran data data item barang dapat ditemukan atau ada data yang ditemukan -->
<?php } else { ?>

	<?php

		/***	Mencari kode otomatis untuk transaksi pembelian 	***/

			// Mencocokan data pada tabel pembelian
			$sql6	=	mysqli_query($koneksi,	"
													SELECT
														kolom_kode_pembelian
													FROM
														tabel_pembelian
													ORDER BY
														kolom_kode_pembelian
													DESC LIMIT 1
												")

												or die("
													<div class='alert'>
														sql6 error<br>
														".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
													</div>
												");


			// jika didalam tabel_pembelian belum ada data maka kita akan membuat kode awal untuk transaksi pembelian adalah BL0000000000
			if (mysqli_num_rows($sql6)==0) {

				$kode_terakhir	=	"BL0000000000";

			} # if mysqli_num_rows $sql6 == 0

			// namun jika sudah ada data kita akan mencari data terakhir dan menyeleksi kode terkahir yang terletak pada kolom_kode_pembelian
			else {

				while ($row6 = mysqli_fetch_array($sql6)) {

					$kode_terakhir = $row6["kolom_kode_pembelian"];

				}

				// memotong inisial tanggal terakhir yang berada didalam kolom_kode_pembelian
				$potongan_tanggal =	substr($kode_terakhir,2,6);

			} # else mysqli_num_rows $sql6 == 0

			// jika tanggal sekarang tidak sama dengan potongan tanggal yang dipilih dari kolom_kode_pembelian
			if ($tanggal_sekarang!==$potongan_tanggal) {

				$kodeBeli 	=	"BL".$tanggal_sekarang."0001";

			} # if $tanggal_sekarang != $potongan_tanggal

			// jika tanggal sekarang sama dengan potongan tanggal yang dipilih dari kolom_kode_pembelian
			else {

				$kodeBeli 	= 	kode_otomatis($kode_terakhir, 'BL'.$tanggal_sekarang, 4);

			}  # else $tanggal_sekarang = $potongan_tanggal

		/*** Proses penambahan data transaksi pembelian ***/

			// Jika tombol simpan yang bernama submitSimpan di klik
			if (isset($_POST["submitSimpan"])) {

				// memasukkan data ke dalam tabel pembelian
				$sql7 	=	mysqli_query($koneksi,	"
														INSERT INTO
															tabel_pembelian(
																kolom_kode_pembelian,
																kolom_tanggal_pembelian,
																kolom_kode_admin
															)
														VALUES (
															'$kodeBeli',
															'$tanggal_sekarang',
															'$session_kode_admin'
														)
													");

				// jika penginputan data benar
				if ($sql7==true) {

					$valueKodeItem 	=	$_POST["inputKodeItem"];

					// menggunakan fungsi count agar dapat mengetahui banyaknya jumlah data yang terdapat pada inputKodeItem
					count($valueKodeItem);

					// mengulang semua valueKodeItem
					foreach ($valueKodeItem as $kodeItemBarang) {

						// memilih tabel item barang
						$sql8	=	mysqli_query($koneksi,	"
																SELECT * FROM
																	tabel_item_barang
																WHERE
																	kolom_kode_item_barang='$kodeItemBarang'
																AND
																	kolom_kode_admin='$session_kode_admin'
																AND
																	kolom_kode_pembelian=''
																ORDER BY
																	kolom_kode_item_barang
															")

															or die ("
																<div class='alert'>
																	sql8 error<br>
																	".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
																</div>
															");

						// jika pemilihan data sql8 tidak ada
						if (mysqli_num_rows($sql8)==0) {

							echo 	"<div class='alert'>";
							echo 	"Tidak ditemukan data item barang<br>";
							echo 	"".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."";
							echo 	"</div>";

						} # if mysqli_num_rows $sql8 == 0

						// jika pemilihan data sql8 ada
						else {

							// mengubah data item barang
							$sql9	=	mysqli_query($koneksi,	"
																	UPDATE
																		tabel_item_barang
																	SET
																		kolom_kode_pembelian='$kodeBeli'
																	WHERE
																		kolom_kode_item_barang='$kodeItemBarang'
																");

							// jika penginputan data benar
							if ($sql9==true) {

								echo 	"<script>";
								echo 	"
											if (confirm('Berhasil menambahkan data transaksi pembelian')) {
												window.location.replace('".base_url."?folder=pembelian&file=detail&kode_pembelian=$kodeBeli');
											}
										";
								echo 	"</script>";

							} # if $sql9==true

							// jika penginputan data salah
							else {

								echo 	"<div class='alert'>";
								echo 	"sql9 error<br>";
								echo 	"".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."";
								echo 	"</div>";

							} # else $sql9==false

						} # else mysqli_num_rows $sql8 > 0

					} # foreach $valueKodeItem as $kodeItemBarang

				} # if $sql7==true

				// jika penginputan data salah
				else {

					echo 	"<div class='alert'>";
					echo 	"sql7 error<br>";
					echo 	"".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."";
					echo 	"</div>";

				} # else $sql7==false

			} # if isset $_POST["submitSimpan"]

	?>

	<form method="post" name="formTransaksiPembelian">

		<div class="table-responsive">

			<table>
						
				<tr>
					<th colspan="8">Daftar Sementara Item Barang</th>
				</tr>

				<tr>
					<th>Kode Item Barang</th>
					<th>Agen</th>
					<th>Nama Item Barang</th>
					<th>Harga</th>
					<th>Jumlah</th>
					<th>Satuan</th>
					<th>Total Harga Item Barang</th>
					<th>Pilihan</th>
				</tr>

				<?php while ($row5 = mysqli_fetch_array($sql5)) { ?>

					<?php

						$total_harga		=	$row5["kolom_harga_barang"] * $row5["kolom_jumlah_barang"];
						$biaya_pembelian	=	$biaya_pembelian + $total_harga;
						$jumlah_barang 		=	$jumlah_barang + $row5["kolom_jumlah_barang"];

					?>

					<tr>

						<input type="hidden" name="inputKodeItem[]" value="<?php echo $row5["kolom_kode_item_barang"]; ?>">

						<td><?php echo $row5["kolom_kode_item_barang"]; ?></td>
						<td><?php echo $row5["kolom_nama_agen"]." - ".$row5["kolom_distributor"]; ?></td>
						<td><?php echo $row5["kolom_nama_barang"]; ?></td>
						<td>Rp. <?php echo format_nomor($row5["kolom_harga_barang"]); ?></td>
						<td><?php echo format_nomor($row5["kolom_jumlah_barang"]); ?></td>
						<td><?php echo $row5["kolom_nama_satuan"]; ?></td>
						<td>Rp. <?php echo format_nomor($total_harga); ?></td>

						<td>

							<a href="<?php echo base_url."?folder=pembelian&file=ubah&kode_item_barang=$row5[kolom_kode_item_barang]"; ?>" class="ubah">
								Ubah
							</a><!-- table .ubah -->

							<a href="<?php echo base_url."?folder=pembelian&file=hapus&kode_item_barang=$row5[kolom_kode_item_barang]"; ?>" class="hapus">
								Hapus
							</a><!-- table .hapus -->

						</td>

					</tr>

				<?php } ?>

			</table><!-- table -->

			<div class="card">

				<div class="card-body">
					
					<h3 class="card-subtitle">Data Transaksi Pembelian Barang</h3><!-- .card .card-body .card-subtitle -->

					<div class="form">
						
						<label>Kode Transaksi Pembelian</label><!-- .card .form label -->
						<input type="text" disabled placeholder="<?php echo $kodeBeli; ?>"><!-- .card .form- input[type=text] -->

						<label>Total Jumlah Item Barang</label><!-- .card .form label -->
						<input type="text" disabled placeholder="<?php echo format_nomor($jumlah_barang); ?>"><!-- .card .form input[type=text] -->

						<label>Jumlah Biaya Pembelian</label><!-- .card .form label -->
						<input type="text" disabled placeholder="Rp. <?php echo format_nomor($biaya_pembelian); ?>"><!-- .card .form input[type=text] -->

						<button type="submit" name="submitSimpan">Simpan Data Transaksi Pembelian Barang</button><!-- .card .form button -->

					</div>

				</div><!-- .card .card-body -->

			</div><!-- .card -->

		</div><!-- .table-responsive -->

	</form>

<?php } ?>