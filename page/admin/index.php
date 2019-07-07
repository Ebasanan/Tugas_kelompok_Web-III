<?php

	// mengecek nilai pencarian data pada url
	$pencarian			=	isset($_GET["pencarian"]) ? $_GET["pencarian"] : false;

	// jika pencarian data dijalankan
	if ($pencarian) {

		$url_pencarian 	=	"&pencarian=$pencarian";
		$where 			=	"WHERE kolom_username LIKE '%$pencarian%'";

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

	// proses pengeluaran data admin
	$sql				=	mysqli_query($koneksi, 	"
														SELECT * FROM
															tabel_admin
															$where
														ORDER BY
															kolom_kode_admin
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

	// proses pemilihan data admin
	$sqlTotal 			=	mysqli_query($koneksi,"SELECT * FROM tabel_admin $where");

?>

<div class="header">
			
	<h1>Admin</h1><!-- .main .header h1 -->

	<a href="<?php echo base_url."?folder=admin&file=tambah"; ?>" class="tambah">Tambah</a><!-- .main .header a -->

	<form method="get" name="formPencarianAdmin">

		<button type="submit">Cari</button><!-- .main .header button -->

		<input type="hidden" name="folder" value="<?php echo $_GET["folder"]; ?>">
		<input type="hidden" name="file" value="<?php echo $_GET["file"]; ?>">

		<input type="text" name="pencarian" value="<?php echo $pencarian; ?>" placeholder="Admin (username) "><!-- .main .header input[type=text] -->

	</form><!-- .main .header form -->

</div><!-- .main .header -->

<!-- Jika pengeluaran data admin tidak dapat ditemukan atau tidak ada data sama sekali -->
<?php if (mysqli_num_rows($sql)==0) { ?>

	<div class="alert">
		Tidak ada data admin
	</div><!-- .alert -->

<!-- Jika pengeluaran data admin dapat ditemukan atau ada data yang ditemukan -->
<?php } else { ?>

	<div class="table-responsive">

		<table>
					
			<tr>
				<th>Kode Admin</th>
				<th>Username</th>
				<th>Level</th>
				<th>Status</th>
				<th>Pilihan</th>
			</tr>

			<?php while ($row = mysqli_fetch_array($sql)) { ?>

				<tr>

					<td><?php echo $row["kolom_kode_admin"]; ?></td>
					<td><?php echo $row["kolom_username"]; ?></td>
					<td><?php echo $row["kolom_level"]; ?></td>

					<td>

						<?php if ($row["kolom_status_admin"]=="on") { ?>

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

						<a href="<?php echo base_url."?folder=admin&file=ubah&kode_admin=$row[kolom_kode_admin]"; ?>" class="ubah">
							Ubah
						</a><!-- table .ubah -->

						<a href="<?php echo base_url."?folder=admin&file=hapus&kode_admin=$row[kolom_kode_admin]"; ?>" class="hapus">
							Hapus
						</a><!-- table .hapus -->

					</td>

				</tr>

			<?php } ?>

		</table><!-- table -->

	</div><!-- .table-responsive -->

	<!-- Menjalankan fungsi pagination -->
	<?php echo pagination($sqlTotal, $data_per_halaman, $pagination, "?folder=admin&file=index$url_pencarian"); ?>

<?php } ?>