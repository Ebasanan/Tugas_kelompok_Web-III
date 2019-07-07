<?php

	// mengecek nilai pencarian data pada url
	$pencarian			=	isset($_GET["pencarian"]) ? $_GET["pencarian"] : false;

	// jika pencarian data dijalankan
	if ($pencarian) {

		$url_pencarian 	=	"&pencarian=$pencarian";
		$where 			=	"WHERE kolom_kode_pembelian LIKE '%$pencarian%'";

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

	// proses pengeluaran data pembelian
	$sql1				=	mysqli_query($koneksi, 	"
														SELECT * FROM
															tabel_pembelian
															$where
														ORDER BY
															kolom_kode_pembelian
														DESC
														LIMIT
															$mulai_dari, $data_per_halaman
													")

													or die ("
														<div class='alert'>
															sql1 error<br>
															".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
														</div>
													");

	// proses pemilihan data pembelian
	$sqlTotal 			=	mysqli_query($koneksi,"SELECT * FROM tabel_pembelian $where");

?>

<div class="header">
			
	<h1>Pembelian</h1><!-- .main .header h1 -->

	<a href="<?php echo base_url."?folder=pembelian&file=tambah"; ?>" class="tambah">Tambah</a><!-- .main .header a -->

	<form method="get" name="formPencarianPembelian">

		<button type="submit">Cari</button><!-- .main .header button -->

		<input type="hidden" name="folder" value="<?php echo $_GET["folder"]; ?>">
		<input type="hidden" name="file" value="<?php echo $_GET["file"]; ?>">

		<input type="text" name="pencarian" value="<?php echo $pencarian; ?>" placeholder="Pembelian (kode pembelian)"><!-- .main .header input[type=text] -->

	</form><!-- .main .header form -->

</div><!-- .main .header -->

<!-- Jika pengeluaran data pembelian tidak dapat ditemukan atau tidak ada data sama sekali -->
<?php if (mysqli_num_rows($sql1)==0) { ?>

	<div class="alert">
		Tidak ada data pembelian
	</div><!-- .alert -->

<!-- Jika pengeluaran data pembelian dapat ditemukan atau ada data yang ditemukan -->
<?php } else { ?>

	<div class="table-responsive">

		<table>
				
			<tr>
				<th>Kode Pembelian</th>
				<th>Tanggal</th>
				<th>Jumlah Item</th>
				<th>Pilihan</th>
			</tr>

			<?php while ($row1 = mysqli_fetch_array($sql1)) { ?>

				<?php

					$kode 	=	$row1["kolom_kode_pembelian"];

					// proses pengeluaran data item barang
					$sql2	=	mysqli_query($koneksi, 	"
															SELECT COUNT(*) AS
																jumlah_item
															FROM
																tabel_item_barang
															WHERE
																kolom_kode_pembelian='$kode'
														")

														or die ("
																	<div class='alert'>
																		sql2 error<br>
																		".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
																	</div>
																");

					$row2 	=	mysqli_fetch_array($sql2);

				?>

				<tr>

					<td><?php echo $row1["kolom_kode_pembelian"]; ?></td>
					<td><?php echo format_tanggal($row1["kolom_tanggal_pembelian"]); ?></td>
					<td><?php echo $row2["jumlah_item"]; ?></td>

					<td>

						<a href="<?php echo base_url."?folder=pembelian&file=detail&kode_pembelian=$row1[kolom_kode_pembelian]"; ?>" class="detail">
							Detail
						</a><!-- table .detail -->

					</td>

				</tr>

			<?php } ?>

		</table><!-- table -->

	</div><!-- .table-responsive -->

	<?php echo pagination($sqlTotal, $data_per_halaman, $pagination, "?folder=pembelian&file=index$url_pencarian"); ?>

<?php } ?>