<?php

	// mengecek nilai pencarian data pada url
	$pencarian			=	isset($_GET["pencarian"]) ? $_GET["pencarian"] : false;

	// jika pencarian data dijalankan
	if ($pencarian) {

		$url_pencarian 	=	"&pencarian=$pencarian";
		$where 			=	"WHERE kolom_nama_kategori LIKE '%$pencarian%'";

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

	// proses pengeluaran data kategori
	$sql				=	mysqli_query($koneksi, 	"
														SELECT * FROM
															tabel_kategori
															$where
														ORDER BY
															kolom_kode_kategori
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

	// proses pemilihan data kategori
	$sqlTotal 			=	mysqli_query($koneksi,"SELECT * FROM tabel_kategori $where");

?>

<div class="header">
			
	<h1>Kategori</h1><!-- .main .header h1 -->

	<a href="<?php echo base_url."?folder=kategori&file=tambah"; ?>" class="tambah">Tambah</a><!-- .main .header a -->

	<form method="get" name="formPencarianKategori">

		<button type="submit">Cari</button><!-- .main .header button -->

		<input type="hidden" name="folder" value="<?php echo $_GET["folder"]; ?>">
		<input type="hidden" name="file" value="<?php echo $_GET["file"]; ?>">

		<input type="text" name="pencarian" value="<?php echo $pencarian; ?>" placeholder="Kategori (nama kategori)"><!-- .main .header input[type=text] -->

	</form><!-- .main .header form -->

</div><!-- .main .header -->

<!-- Jika pengeluaran data kategori tidak dapat ditemukan atau tidak ada data sama sekali -->
<?php if (mysqli_num_rows($sql)==0) { ?>

	<div class="alert">
		Tidak ada data kategori
	</div><!-- .alert -->

<!-- Jika pengeluaran data kategori dapat ditemukan atau ada data yang ditemukan -->
<?php } else { ?>

	<div class="table-responsive">

		<table>
					
			<tr>
				<th>Kode Kategori</th>
				<th>Nama Kategori</th>
				<th>Status</th>
				<th>Pilihan</th>
			</tr>

			<?php while ($row = mysqli_fetch_array($sql)) { ?>

				<tr>

					<td><?php echo $row["kolom_kode_kategori"]; ?></td>
					<td><?php echo $row["kolom_nama_kategori"]; ?></td>

					<td>

						<?php if ($row["kolom_status_kategori"]=="on") { ?>

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

						<a href="<?php echo base_url."?folder=kategori&file=ubah&kode_kategori=$row[kolom_kode_kategori]"; ?>" class="ubah">
							Ubah
						</a><!-- table .ubah -->

						<a href="<?php echo base_url."?folder=kategori&file=hapus&kode_kategori=$row[kolom_kode_kategori]"; ?>" class="hapus">
							Hapus
						</a><!-- table .hapus -->

					</td>

				</tr>

			<?php } ?>

		</table><!-- table -->

	</div><!-- .table-responsive -->

	<?php echo pagination($sqlTotal, $data_per_halaman, $pagination, "?folder=kategori&file=index$url_pencarian"); ?>

<?php } ?>