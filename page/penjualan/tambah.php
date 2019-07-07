<?php

	// mengatur zona waktu untuk Waktu Indonesia Barat
	date_default_timezone_set("Asia/Bangkok");

	// menampilkan tanggal sekarang
	$tanggal_sekarang = date('ymd');

	// nilai awal potongan tanggal adalah kosong atau tidak memiliki nilai apapun
	$potongan_tanggal = "";

?>

<div class="header">
			
	<h1>Penjualan</h1><!-- .main .header h1 -->

</div><!-- .main .header -->

<?php

/***	Mencari kode otomatis untuk item menu 	***/

	// Mencocokan data pada tabel item menu
	$sql1	=	mysqli_query($koneksi,	"
											SELECT
												kolom_kode_item_menu
											FROM
												tabel_item_menu
											ORDER BY
												kolom_kode_item_menu
											DESC LIMIT 1
										")

										or die("
											<div class='alert'>
												sql1 error<br>
												".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
											</div>
										");

	// jika didalam tabel_item_menu belum ada data maka kita akan membuat kode awal untuk item menu adalah IM0000000000000
	if (mysqli_num_rows($sql1)==0) {

		$kode_terakhir	=	"IM0000000000000";

	} # if mysqli_num_rows $sql1 == 0

	// namun jika sudah ada data kita akan mencari data terakhir dan menyeleksi kode terkahir yang terletak pada kolom_kode_item_menu
	else {

		while ($row1 = mysqli_fetch_array($sql1)) {

			$kode_terakhir = $row1["kolom_kode_item_menu"];

		}

		// memotong inisial tanggal terakhir yang berada didalam kolom_kode_item_menu
		$potongan_tanggal =	substr($kode_terakhir,2,6);

	} # else mysqli_num_rows $sql1 == 0

	// jika tanggal sekarang tidak sama dengan potongan tanggal yang dipilih dari kolom_kode_item_menu
	if ($tanggal_sekarang!==$potongan_tanggal) {

		$kodeItem 	= 	"IM".$tanggal_sekarang."0000001";
		
	} # if $tanggal_sekarang != $potongan_tanggal

	// jika tanggal sekarang sama dengan potongan tanggal yang dipilih dari kolom_kode_item_menu
	else {

		$kodeItem 	= 	kode_otomatis($kode_terakhir, 'IM'.$tanggal_sekarang, 7);

	} # else $tanggal_sekarang == $potongan_tanggal

/*** Proses penambahan data item menu ***/

	// Jika tombol pilih yang bernama submitPilih tidak di klik
	if (!isset($_POST["submitPilih"])) {

		$valueMenu		=	"";
		$valueJumlah	=	"";

	} # if !isset $_POST["submitPilih"]

	// Jika tombol pilih yang bernama submitPilih di klik
	else {

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
													INSERT INTO
														tabel_item_menu(
															kolom_kode_item_menu,
															kolom_kode_penjualan,
															kolom_kode_admin,
															kolom_kode_menu,
															kolom_jumlah_porsi
														)
													VALUES (
														'$kodeItem',
														'',
														'$session_kode_admin',
														'$valueMenu',
														'$valueJumlah'
													)
												");

			// jika penginputan data benar
			if ($sql3==true) {

				echo 	"<script>";
				echo 	"
							if (confirm('Berhasil menambahkan data item menu')) {
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

	} # else isset $_POST["submitPilih"]

?>

<div class="card">

	<div class="card-body">
				
		<h3 class="card-subtitle">Transaksi Penjualan Menu</h3><!-- .card .card-body .card-subtitle -->

		<form method="post" class="form" name="formItemMenu">

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

			<button type="submit" name="submitPilih">Pilih</button><!-- .card .form button -->

		</form>

	</div><!-- .card .card-body -->

</div><!-- .card -->

<?php

	// menampilkan data agen yang berstatus on pada select agen
	$sql4	=	mysqli_query($koneksi,	"
											SELECT
												tabel_item_menu.*,
												tabel_menu.*
											FROM
												tabel_item_menu
											INNER JOIN
												tabel_menu
											ON
												tabel_item_menu.kolom_kode_menu=tabel_menu.kolom_kode_menu
											WHERE
												tabel_item_menu.kolom_kode_admin='$session_kode_admin'
											AND
												tabel_item_menu.kolom_kode_penjualan=''
										")

										or die("
												<div class='alert'>
													sql4 error<br>
													".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
												</div>
										");


	// nilai awal dari total pembayaran dan jumlah porsi adalah 0
	$total_pembayaran 	=	0;
	$jumlah_porsi 		=	0;

?>

<!-- Jika pengeluaran data item menu tidak dapat ditemukan atau tidak ada data sama sekali -->
<?php if (mysqli_num_rows($sql4)==0) { ?>

	<div class="alert">
		Kamu belum memiliki daftar item menu
	</div><!-- .alert -->

<!-- Jika pengeluaran data item menu dapat ditemukan atau ada data yang ditemukan -->
<?php } else { ?>

	<?php

		/***	Mencari kode otomatis untuk transaksi penjualan 	***/

			// Mencocokan data pada tabel penjualan
			$sql5	=	mysqli_query($koneksi,	"
													SELECT
														kolom_kode_penjualan
													FROM
														tabel_penjualan
													ORDER BY
														kolom_kode_penjualan
													DESC LIMIT 1
												")

												or die("
													<div class='alert'>
														sql5 error<br>
														".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
													</div>
												");

			// jika didalam tabel_penjualan belum ada data maka kita akan membuat kode awal untuk transaksi penjualan adalah JL0000000000
			if (mysqli_num_rows($sql5)==0) {

				$kode_terakhir	=	"JL0000000000";

			} # if mysqli_num_rows $sql5 == 0

			// namun jika sudah ada data kita akan mencari data terakhir dan menyeleksi kode terkahir yang terletak pada kolom_kode_penjualan
			else {

				while ($row5 = mysqli_fetch_array($sql5)) {

					$kode_terakhir = $row5["kolom_kode_penjualan"];

				}

				// memotong inisial tanggal terakhir yang berada didalam kolom_kode_penjualan
				$potongan_tanggal =	substr($kode_terakhir,2,6);

			} # else mysqli_num_rows $sql5 == 0

			// jika tanggal sekarang tidak sama dengan potongan tanggal yang dipilih dari kolom_kode_penjualan
			if ($tanggal_sekarang!==$potongan_tanggal) {

				$kodeJual 	=	"JL".$tanggal_sekarang."0001";

			} # if $tanggal_sekarang != $potongan_tanggal

			// jika tanggal sekarang sama dengan potongan tanggal yang dipilih dari kolom_kode_penjualan
			else {

				$kodeJual 	= 	kode_otomatis($kode_terakhir, 'JL'.$tanggal_sekarang, 4);

			}  # else $tanggal_sekarang = $potongan_tanggal

		/*** Proses penambahan data transaksi penjualan ***/

			// Jika tombol simpan yang bernama submitSimpan tidak di klik
			if (!isset($_POST["submitSimpan"])) {

				$valueUang	=	"";

			} # if !isset $_POST["submitSimpan"]

			// Jika tombol simpan yang bernama submitSimpan di klik
			else {

				$valueUang	=	$_POST["inputUang"];

				if (empty($valueUang)) {

					echo "<div class='alert'>";
					echo "Jumlah Uang Pembayaran tidak boleh kosong";
					echo "</div>";

				} # if empty $valueUang

				else {

					// memasukkan data ke dalam tabel penjualan
					$sql6 	=	mysqli_query($koneksi,	"
															INSERT INTO
																tabel_penjualan(
																	kolom_kode_penjualan,
																	kolom_tanggal_penjualan,
																	kolom_kode_admin,
																	kolom_uang_tunai
																)
															VALUES (
																'$kodeJual',
																'$tanggal_sekarang',
																'$session_kode_admin',
																'$valueUang'
															)
														");

					// jika penginputan data benar
					if ($sql6==true) {

						$valueKodeItem 	=	$_POST["inputKodeItem"];

						// menggunakan fungsi count agar dapat mengetahui banyaknya jumlah data yang terdapat pada inputKodeItem
						count($valueKodeItem);

						// mengulang semua valueKodeItem
						foreach ($valueKodeItem as $kodeItemMenu) {

							// memilih tabel item menu
							$sql7	=	mysqli_query($koneksi,	"
																	SELECT * FROM
																		tabel_item_menu
																	WHERE
																		kolom_kode_item_menu='$kodeItemMenu'
																	AND
																		kolom_kode_admin='$session_kode_admin'
																	AND
																		kolom_kode_penjualan=''
																	ORDER BY
																		kolom_kode_item_menu
																")

																or die ("
																	<div class='alert'>
																		sql7 error<br>
																		".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
																	</div>
																");

							// jika pemilihan data sql7 tidak ada
							if (mysqli_num_rows($sql7)==0) {

								echo 	"<div class='alert'>";
								echo 	"Tidak ditemukan data item menu<br>";
								echo 	"".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."";
								echo 	"</div>";

							} # if mysqli_num_rows $sql7 == 0

							// jika pemilihan data sql7 ada
							else {

								// mengubah data item menu
								$sql8	=	mysqli_query($koneksi,	"
																		UPDATE
																			tabel_item_menu
																		SET
																			kolom_kode_penjualan='$kodeJual'
																		WHERE
																			kolom_kode_item_menu='$kodeItemMenu'
																	");

								// jika penginputan data benar
								if ($sql8==true) {

									echo 	"<script>";
									echo 	"
												if (confirm('Berhasil menambahkan data transaksi penjualan')) {
													window.location.replace('".base_url."?folder=penjualan&file=detail&kode_penjualan=$kodeJual');
												}
											";
									echo 	"</script>";

								} # if $sql8==true

								// jika penginputan data salah
								else {

									echo 	"<div class='alert'>";
									echo 	"sql8 error<br>";
									echo 	"".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."";
									echo 	"</div>";

								} # else $sql8==false

							} # else mysqli_num_rows $sql7 > 0

						} # foreach $valueKodeItem as $kodeItemMenu

					} # if $sql6==true

					// jika penginputan data salah
					else {

						echo 	"<div class='alert'>";
						echo 	"sql6 error<br>";
						echo 	"".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."";
						echo 	"</div>";

					} # else $sql6==false

				} # else empty $valueUang

			} # else isset $_POST["submitSimpan"]

	?>

	<form method="post" name="formTransaksiPenjualan">

		<div class="table-responsive">

			<table>
						
				<tr>
					<th colspan="6">Daftar Sementara Item Menu</th>
				</tr>

				<tr>
					<th>Kode Item Menu</th>
					<th>Nama Menu</th>
					<th>Harga Menu</th>
					<th>Jumlah Porsi</th>
					<th>Total Harga Item Menu</th>
					<th>Pilihan</th>
				</tr>

				<?php while ($row4 = mysqli_fetch_array($sql4)) { ?>

					<?php

						$total_harga_porsi 	=	$row4["kolom_harga_menu"] * $row4["kolom_jumlah_porsi"];
						$total_pembayaran 	=	$total_pembayaran + $total_harga_porsi;
						$total_porsi_menu 	=	$jumlah_porsi + $row4["kolom_jumlah_porsi"];

					?>

					<tr>

					<input type="hidden" name="inputKodeItem[]" value="<?php echo $row4["kolom_kode_item_menu"]; ?>">

						<td><?php echo $row4["kolom_kode_item_menu"]; ?></td>
						<td><?php echo $row4["kolom_kode_menu"]." - ".$row4["kolom_nama_menu"]; ?></td>
						<td>Rp. <?php echo format_nomor($row4["kolom_harga_menu"]); ?></td>
						<td><?php echo format_nomor($row4["kolom_jumlah_porsi"]); ?></td>
						<td>Rp. <?php echo format_nomor($total_harga_porsi); ?></td>

						<td>

							<a href="<?php echo base_url."?folder=penjualan&file=ubah&kode_item_menu=$row4[kolom_kode_item_menu]"; ?>" class="ubah">
								Ubah
							</a><!-- table .ubah -->

							<a href="<?php echo base_url."?folder=penjualan&file=hapus&kode_item_menu=$row4[kolom_kode_item_menu]"; ?>" class="hapus">
								Hapus
							</a><!-- table .hapus -->

						</td>
					</tr>

				<?php } ?>

			</table><!-- table -->

		</div><!-- .table-responsive -->

		<div class="card">

			<div class="card-body">
						
				<h3 class="card-subtitle">Data Transaksi Penjualan Menu</h3><!-- .card .card-body .card-subtitle -->

				<div class="form">
							
					<label>Kode Transaksi Penjualan</label><!-- .card .form label -->
					<input type="text" disabled placeholder="<?php echo $kodeJual; ?>"><!-- .card .form- input[type=text] -->

					<label>Total Porsi Menu</label><!-- .card .form label -->
					<input type="text" disabled placeholder="<?php echo format_nomor($total_porsi_menu); ?>"><!-- .card .form input[type=text] -->

					<label>Jumlah Biaya Pembayaran</label><!-- .card .form label -->
					<input type="text" disabled placeholder="Rp. <?php echo format_nomor($total_pembayaran); ?>"><!-- .card .form input[type=text] -->

					<label>Jumlah Uang Pembayaran</label><!-- .card .form label -->
					<input type="text" name="inputUang" value="<?php echo $valueUang; ?>" placeholder="Masukkan nilai uang yang dibayar oleh pembeli"><!-- .card .form input[type=text] -->

					<button type="submit" name="submitSimpan">Simpan Data Transaksi Pembelian Barang</button><!-- .card .form button -->

				</div>

			</div><!-- .card .card-body -->

		</div><!-- .card -->

	</form>

<?php } ?>