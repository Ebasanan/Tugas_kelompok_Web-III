<?php

	// mengecek nilai pencarian data pada url
	$pencarian			=	isset($_GET["pencarian"]) ? $_GET["pencarian"] : false;

	// jika pencarian data dijalankan
	if ($pencarian) {

		$url_pencarian 	=	"&pencarian=$pencarian";
		$where 			=	"WHERE tabel_menu.kolom_nama_menu LIKE '%$pencarian%'";

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

	// proses pengeluaran data menu
	$sql				=	mysqli_query($koneksi, 	"
														SELECT
															tabel_menu.*,
															tabel_kategori.kolom_nama_kategori
														FROM
															tabel_menu
														INNER JOIN
															tabel_kategori
														ON
															tabel_menu.kolom_kode_kategori=tabel_kategori.kolom_kode_kategori
														$where
														ORDER BY
															tabel_menu.kolom_kode_menu
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

	// proses pemilihan data menu
	$sqlTotal 			=	mysqli_query($koneksi,"SELECT * FROM tabel_menu $where");

?>

<div class="header">
			
	<h1>Menu</h1><!-- .main .header h1 -->

	<a href="<?php echo base_url."?folder=menu&file=tambah"; ?>" class="tambah">Tambah</a><!-- .main .header a -->

	<form method="get" name="formPencarianMenu">

		<button type="submit">Cari</button><!-- .main .header button -->

		<input type="hidden" name="folder" value="<?php echo $_GET["folder"]; ?>">
		<input type="hidden" name="file" value="<?php echo $_GET["file"]; ?>">

		<input type="text" name="pencarian" value="<?php echo $pencarian; ?>" placeholder="Menu (nama menu)"><!-- .main .header input[type=text] -->

	</form><!-- .main .header form -->

</div><!-- .main .header -->

<!-- Jika pengeluaran data menu tidak dapat ditemukan atau tidak ada data sama sekali -->
<?php if (mysqli_num_rows($sql)==0) { ?>

	<div class="alert">
		Tidak ada data menu
	</div><!-- .alert -->

<!-- Jika pengeluaran data menu dapat ditemukan atau ada data yang ditemukan -->
<?php } else { ?>

	<div class="table-responsive">

		<table>
					
			<tr>
				<th>Kode Menu</th>
				<th>Kategori</th>
				<th>Nama Menu</th>
				<th>Isi Menu</th>
				<th>Harga Menu</th>
				<th>Status</th>
				<th>Pilihan</th>
			</tr>

			<?php while ($row = mysqli_fetch_array($sql)) { ?>

				<tr>

					<td><?php echo $row["kolom_kode_menu"]; ?></td>
					<td><?php echo $row["kolom_nama_kategori"]; ?></td>
					<td><?php echo $row["kolom_nama_menu"]; ?></td>
					<td><?php echo $row["kolom_isi_menu"]; ?></td>
					<td>Rp. <?php echo format_nomor($row["kolom_harga_menu"]); ?></td>

					<td>

						<?php if ($row["kolom_status_menu"]=="on") { ?>

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

						<a href="<?php echo base_url."?folder=menu&file=ubah&kode_menu=$row[kolom_kode_menu]"; ?>" class="ubah">
							Ubah
						</a><!-- table .ubah -->

						<a href="<?php echo base_url."?folder=menu&file=hapus&kode_menu=$row[kolom_kode_menu]"; ?>" class="hapus">
							Hapus
						</a><!-- table .hapus -->

					</td>

				</tr>

			<?php } ?>

		</table><!-- table -->

	</div><!-- .table-responsive -->

	<?php echo pagination($sqlTotal, $data_per_halaman, $pagination, "?folder=menu&file=index$url_pencarian"); ?>

<?php } ?>