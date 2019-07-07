<?php

	// mengecek nilai kode_pembelian data pada url
	$kode_pembelian	= isset($_GET["kode_pembelian"]) ? $_GET["kode_pembelian"] : false;

?>

<div class="header">
			
	<h1>Pembelian</h1><!-- .main .header h1 -->

</div><!-- .main .header -->

<?php if (!$kode_pembelian) { ?>

	<div class="alert">
		Tidak ditemukan data transaksi pembelian
	</div><!-- .alert -->

<?php } else { ?>

	<?php

		$sql1	=	mysqli_query($koneksi,	"
												SELECT
													tabel_pembelian.*,
													tabel_admin.*
												FROM
													tabel_pembelian
												INNER JOIN
													tabel_admin
												ON
													tabel_pembelian.kolom_kode_admin=tabel_admin.kolom_kode_admin
												WHERE
													tabel_pembelian.kolom_kode_pembelian='$kode_pembelian'
											")

											or die ("
												<div class='alert'>
													sql1 error<br>
													".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
												</div>
											");

		$sql2	=	mysqli_query($koneksi,	"
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
													tabel_item_barang.kolom_kode_pembelian='$kode_pembelian'
											")

											or die ("
												<div class='alert'>
													sql2 error<br>
													".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
												</div>
											");

		// nilai awal dari biaya pembelian dan jumlah barang adalah 0
		$biaya_pembelian	=	0;
		$jumlah_barang 		=	0;

	?>

	<?php if (mysqli_num_rows($sql1)==0) { ?>

		<div class="alert">
			Tidak ditemukan data transaksi pembelian
		</div><!-- .alert -->

	<?php } else { ?>

		<?php $row1	= mysqli_fetch_array($sql1); ?>

		<!-- Table Item Barang -->
		<div class="table-responsive">

			<table>
						
				<tr>
					<th colspan="7">Rincian Item Pembelian Barang</th>
				</tr>

				<tr>
					<th>Kode Item Barang</th>
					<th>Agen</th>
					<th>Nama Item Barang</th>
					<th>Harga</th>
					<th>Jumlah</th>
					<th>Satuan</th>
					<th>Total Item Barang</th>
				</tr>

				<?php while ($row2 = mysqli_fetch_array($sql2)) { ?>

					<?php

						$total_harga		=	$row2["kolom_harga_barang"] * $row2["kolom_jumlah_barang"];
						$biaya_pembelian	=	$biaya_pembelian + $total_harga;
						$jumlah_barang 		=	$jumlah_barang + $row2["kolom_jumlah_barang"];

					?>

					<tr>

						<td><?php echo $row2["kolom_kode_item_barang"]; ?></td>
						<td><?php echo $row2["kolom_kode_agen"]." - ".$row2["kolom_nama_agen"]." - ".$row2["kolom_distributor"]; ?></td>
						<td><?php echo $row2["kolom_nama_barang"]; ?></td>
						<td>Rp. <?php echo format_nomor($row2["kolom_harga_barang"]); ?></td>
						<td><?php echo format_nomor($row2["kolom_jumlah_barang"]); ?></td>
						<td><?php echo $row2["kolom_nama_satuan"]; ?></td>
						<td>Rp. <?php echo format_nomor($total_harga); ?></td>

					</tr>

				<?php } ?>

			</table><!-- table -->

		</div><!-- .table-responsive -->

		<!-- Table Transaksi Pembelian Barang -->
		<div class="table-responsive">

			<table>
						
				<tr>
					<th colspan="2">Data Transaksi Pembelian Item Barang</th>
				</tr>

				<tr>
					<th width="15%">Kode Transaksi</th>
					<td><?php echo $row1["kolom_kode_pembelian"]; ?></td>
				</tr>

				<tr>
					<th>Tanggal Transaksi</th>
					<td><?php echo format_tanggal($row1["kolom_tanggal_pembelian"]); ?></td>
				</tr>

				<tr>
					<th>Admin</th>
					<td><?php echo $row1["kolom_kode_admin"]." - @".$row1["kolom_username"]; ?></td>
				</tr>

				<tr>
					<th>Total Jumlah Item Barang</th>
					<td><?php echo format_nomor($jumlah_barang); ?></td>
				</tr>

				<tr>
					<th>Total Biaya Pembelian</th>
					<td>Rp. <?php echo format_nomor($biaya_pembelian); ?></td>
				</tr>

				<tr>
					<td colspan="2">
						<a target="_blank" href="<?php echo base_url."page/pembelian/cetak.php?kode_pembelian=$row1[kolom_kode_pembelian]"; ?>" class="detail">
							Cetak
						</a><!-- table .detail -->
					</td>
				</tr>

			</table><!-- table -->

		</div><!-- .table-responsive -->

	<?php } ?>

<?php } ?>