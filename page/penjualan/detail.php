<?php

	// mengecek nilai kode_penjualan data pada url
	$kode_penjualan	= isset($_GET["kode_penjualan"]) ? $_GET["kode_penjualan"] : false;

?>

<div class="header">
			
	<h1>Penjualan</h1><!-- .main .header h1 -->

</div><!-- .main .header -->

<?php if (!$kode_penjualan) { ?>

	<div class="alert">
		Tidak ditemukan data transaksi penjualan
	</div><!-- .alert -->

<?php } else { ?>

	<?php

		$sql1	=	mysqli_query($koneksi,	"
												SELECT
													tabel_penjualan.*,
													tabel_admin.*
												FROM
													tabel_penjualan
												INNER JOIN
													tabel_admin
												ON
													tabel_penjualan.kolom_kode_admin=tabel_admin.kolom_kode_admin
												WHERE
													tabel_penjualan.kolom_kode_penjualan='$kode_penjualan'
											")

											or die ("
												<div class='alert'>
													sql1 error<br>
													".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
												</div>
											");

		$sql2	=	mysqli_query($koneksi,	"
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
													tabel_item_menu.kolom_kode_penjualan='$kode_penjualan'
											")

											or die ("
												<div class='alert'>
													sql2 error<br>
													".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
												</div>
											");

		// nilai awal dari total pembayaran dan jumlah porsi adalah 0
		$total_pembayaran 	=	0;
		$jumlah_porsi 		=	0;

	?>

	<?php if (mysqli_num_rows($sql1)==0) { ?>

		<div class="alert">
			Tidak ditemukan data transaksi penjualan
		</div><!-- .alert -->

	<?php } else { ?>

		<?php $row1 = mysqli_fetch_array($sql1); ?>

		<!-- Table Item Menu -->
		<div class="table-responsive">

			<table>
						
				<tr>
					<th colspan="5">Rincian Item Penjualan Menu</th>
				</tr>

				<tr>
					<th>Kode Item Menu</th>
					<th>Nama Menu</th>
					<th>Harga Menu</th>
					<th>Jumlah Porsi</th>
					<th>Total Harga Item Menu</th>
				</tr>

				<?php while ($row2 = mysqli_fetch_array($sql2)) { ?>

					<?php

						$total_harga_porsi 	=	$row2["kolom_harga_menu"] * $row2["kolom_jumlah_porsi"];
						$total_pembayaran 	=	$total_pembayaran + $total_harga_porsi;
						$jumlah_porsi 		=	$jumlah_porsi + $row2["kolom_jumlah_porsi"];

					?>

					<tr>
						<td><?php echo $row2["kolom_kode_item_menu"]; ?></td>
						<td><?php echo $row2["kolom_kode_menu"]." - ".$row2["kolom_nama_menu"]; ?></td>
						<td>Rp. <?php echo format_nomor($row2["kolom_harga_menu"]); ?></td>
						<td><?php echo format_nomor($row2["kolom_jumlah_porsi"]); ?></td>
						<td>Rp. <?php echo format_nomor($total_harga_porsi); ?></td>
					</tr>

				<?php } ?>

			</table><!-- table -->

		</div><!-- .table-responsive -->

		<!-- Table Transaksi Penjualan Menu -->
		<div class="table-responsive">

			<table>
						
				<tr>
					<th colspan="2">Data Transaksi Penjualan Item Menu</th>
				</tr>

				<tr>
					<th width="20%">Kode Transaksi Penjualan</th>
					<td><?php echo $row1["kolom_kode_penjualan"]; ?></td>
				</tr>

				<tr>
					<th>Tanggal Transaksi</th>
					<td><?php echo format_tanggal($row1["kolom_tanggal_penjualan"]); ?></td>
				</tr>

				<tr>
					<th>Admin</th>
					<td><?php echo $row1["kolom_kode_admin"]." - @".$row1["kolom_username"]; ?></td>
				</tr>

				<tr>
					<th>Total Porsi Menu</th>
					<td><?php echo format_nomor($jumlah_porsi); ?></td>
				</tr>

				<tr>
					<th>Total Biaya Pembayaran</th>
					<td>Rp. <?php echo format_nomor($total_pembayaran); ?></td>
				</tr>

				<tr>
					<th>Jumlah Biaya Pembayaran</th>
					<td>Rp. <?php echo format_nomor($row1["kolom_uang_tunai"]); ?></td>
				</tr>

				<?php $uang_kembalian = $row1["kolom_uang_tunai"] - $total_pembayaran; ?>

				<tr>
					<th>Kembali Biaya Pembayaran</th>
					<td>Rp. <?php echo format_nomor($uang_kembalian); ?></td>
				</tr>

				<tr>
					<td colspan="2">
						<a target="_blank" href="<?php echo base_url."page/penjualan/cetak.php?kode_penjualan=$row1[kolom_kode_penjualan]"; ?>" class="detail">
							Cetak
					</td>
				</tr>

			</table><!-- table -->

		</div><!-- .table-responsive -->

	<?php } ?>

<?php } ?>