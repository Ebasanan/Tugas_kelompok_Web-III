<?php

	// mengecek nilai pencarian data pada url
	$pencarian			=	isset($_GET["pencarian"]) ? $_GET["pencarian"] : false;

	// jika pencarian data dijalankan
	if ($pencarian) {

		$url_pencarian 	=	"&pencarian=$pencarian";
		$where 			=	"WHERE kolom_nama_satuan LIKE '%$pencarian%'";

	} # if ($pencarian)

	// jika pencarian data tidak dijalankan
	else {

		$url_pencarian 	=	"";
		$where 			=	"";

	} # else ($pencarian)

	// mengecek nilai pagination pada url
	$pagination			=	isset($_GET["pagination"]) ? $_GET["pagination"] : 1;

	// ketentuan proses pagination data
	$data_per_halaman	=	10;
	$mulai_dari 		=	($pagination - 1) * $data_per_halaman;

	// proses pengeluaran data satuan
	$sql				=	mysqli_query($koneksi, 	"
														SELECT * FROM
															tabel_satuan
															$where
														ORDER BY
															kolom_kode_satuan
														DESC
														LIMIT
															$mulai_dari, $data_per_halaman
													")

													or die ("
														<div class='alert'>
															sql error<br>
															".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
														</div>
													");

	// proses pemilihan data satuan
	$sqlTotal 			=	mysqli_query($koneksi,"SELECT * FROM tabel_satuan $where");

?>

<div class="header">
			
	<h1>Satuan</h1><!-- .main .header h1 -->

	<a href="<?php echo base_url."?folder=satuan&file=tambah"; ?>" class="tambah">Tambah</a><!-- .main .header a -->

	<form method="get" name="formPencarianSatuan">

		<button type="submit">Cari</button><!-- .main .header button -->

		<input type="hidden" name="folder" value="<?php echo $_GET["folder"]; ?>">
		<input type="hidden" name="file" value="<?php echo $_GET["file"]; ?>">

		<input type="text" name="pencarian" value="<?php echo $pencarian; ?>" placeholder="Agen (nama satuan)"><!-- .main .header input[type=text] -->

	</form><!-- .main .header form -->

</div><!-- .main .header -->

<!-- Jika pengeluaran data satuan tidak dapat ditemukan atau tidak ada data sama sekali -->
<?php if (mysqli_num_rows($sql)==0) { ?>

	<div class="alert">
		Tidak ada data satuan
	</div><!-- .alert -->

<!-- Jika pengeluaran data satuan dapat ditemukan atau ada data yang ditemukan -->
<?php } else { ?>

	<div class="table-responsive">

		<table>
					
			<tr>
				<th>Kode Satuan</th>
				<th>Nama Satuan</th>
				<th>Status</th>
				<th>Pilihan</th>
			</tr>

			<?php while ($row = mysqli_fetch_array($sql)) { ?>

				<tr>

					<td><?php echo $row["kolom_kode_satuan"]; ?></td>
					<td><?php echo $row["kolom_nama_satuan"]; ?></td>

					<td>

						<?php if ($row["kolom_status_satuan"]=="on") { ?>

							<div class="status-on">
								on
							</div><!-- .table .status-on -->

						<?php } else { ?>

							<div class="status-off">
								off
							</div><!-- table .status-off -->

						<?php } ?>

					</td>

					<td>

						<a href="<?php echo base_url."?folder=satuan&file=ubah&kode_satuan=$row[kolom_kode_satuan]"; ?>" class="ubah">
							Ubah
						</a><!-- table .ubah -->

						<a href="<?php echo base_url."?folder=satuan&file=hapus&kode_satuan=$row[kolom_kode_satuan]"; ?>" class="hapus">
							Hapus
						</a><!-- table .hapus -->

					</td>

				</tr>

			<?php } ?>

		</table><!-- table -->

	</div><!-- .table-responsive -->

	<?php echo pagination($sqlTotal, $data_per_halaman, $pagination, "?folder=satuan&file=index$url_pencarian"); ?>

<?php } ?>