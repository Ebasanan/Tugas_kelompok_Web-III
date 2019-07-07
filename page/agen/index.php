<?php

	// mengecek nilai pencarian data pada url
	$pencarian			=	isset($_GET["pencarian"]) ? $_GET["pencarian"] : false;

	// jika pencarian data dijalankan
	if ($pencarian) {

		$url_pencarian 	=	"&pencarian=$pencarian";
		$where 			=	"WHERE kolom_nama_agen LIKE '%$pencarian%'";

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

	// proses pengeluaran data agen
	$sql				=	mysqli_query($koneksi, 	"
														SELECT * FROM
															tabel_agen
															$where
														ORDER BY
															kolom_kode_agen
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

	// proses pemilihan data agen
	$sqlTotal 			=	mysqli_query($koneksi,"SELECT * FROM tabel_agen $where");

?>

<div class="header">
			
	<h1>Agen</h1><!-- .main .header h1 -->

	<a href="<?php echo base_url."?folder=agen&file=tambah"; ?>" class="tambah">Tambah</a><!-- .main .header a -->

	<form method="get" name="formPencarianAgen">

		<button type="submit">Cari</button><!-- .main .header button -->

		<input type="hidden" name="folder" value="<?php echo $_GET["folder"]; ?>">
		<input type="hidden" name="file" value="<?php echo $_GET["file"]; ?>">

		<input type="text" name="pencarian" value="<?php echo $pencarian; ?>" placeholder="Agen (nama agen)"><!-- .main .header input[type=text] -->

	</form><!-- .main .header form -->

</div><!-- .main .header -->

<!-- Jika pengeluaran data agen tidak dapat ditemukan atau tidak ada data sama sekali -->
<?php if (mysqli_num_rows($sql)==0) { ?>

	<div class="alert">
		Tidak ada data agen
	</div><!-- .alert -->

<!-- Jika pengeluaran data agen dapat ditemukan atau ada data yang ditemukan -->
<?php } else { ?>

	<div class="table-responsive">

		<table>
					
			<tr>
				<th>Kode Agen</th>
				<th>Nama Agen</th>
				<th>No Telepon</th>
				<th>Distributor</th>
				<th>Status</th>
				<th>Pilihan</th>
			</tr>

			<?php while ($row = mysqli_fetch_array($sql)) { ?>

				<tr>

					<td><?php echo $row["kolom_kode_agen"]; ?></td>
					<td><?php echo $row["kolom_nama_agen"]; ?></td>
					<td><?php echo $row["kolom_telepon_agen"]; ?></td>
					<td><?php echo $row["kolom_distributor"]; ?></td>

					<td>

						<?php if ($row["kolom_status_agen"]=="on") { ?>

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

						<a href="<?php echo base_url."?folder=agen&file=ubah&kode_agen=$row[kolom_kode_agen]"; ?>" class="ubah">
							Ubah
						</a><!-- table .ubah -->

						<a href="<?php echo base_url."?folder=agen&file=hapus&kode_agen=$row[kolom_kode_agen]"; ?>" class="hapus">
							Hapus
						</a><!-- table .hapus -->

					</td>

				</tr>

			<?php } ?>

		</table><!-- table -->

	</div><!-- .table-responsive -->

	<?php echo pagination($sqlTotal, $data_per_halaman, $pagination, "?folder=agen&file=index$url_pencarian"); ?>

<?php } ?>