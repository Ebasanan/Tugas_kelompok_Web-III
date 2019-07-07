<?php

	session_start();
	ob_start();

	include_once ("../../config/koneksi.php");
	include_once ("../../config/function.php");

	$kode_penjualan		= 	isset($_GET["kode_penjualan"]) ? $_GET["kode_penjualan"] : false;
	$session_kode_admin	=	isset($_SESSION["session_kode_admin"]) ? $_SESSION["session_kode_admin"]  : false;

	// Jika admin belum login
	if (!$session_kode_admin) {

		// kita akan mendirect admin kehalaman login dan tidak bisa mengakses halaman utama
		header("location:../../login.php");

	} # if !$session_kode_admin

?>

<!DOCTYPE html>
<html>
<head>

	<title>Struk <?php echo $kode_penjualan; ?></title>

	<script type="text/javascript">

		function printContent(el) {

			var restorepage = document.body.innerHTML;
			var printcontent = document.getElementById(el).innerHTML;
			document.body.innerHTML = printcontent;
			window.print();
			document.body.innerHTML = restorepage;

		}

	</script>

	<style>

		body {
			margin: 0px;
			padding: 0px;
			background-color: #ddd;
			font-family: arial;
		}

		.a4 {
			width: 100%;
			background-color: #fff;
			overflow: hidden;
		}

		.border-dashed {
			float: left;
			width: 100%;
			border-bottom: 1px solid #333;
			margin: 15px 0px;
		}

		.header {
		    text-align: center;
		    float: left;
		    width: 100%;
		    font-size: 15px;
		    font-weight: bold;
		    padding: 15px 0px;
		    margin-bottom: 15px;
		    border-bottom: 1px solid #333;
		}

		.info-perusahaan {
			float: left;
    		width: 45%;
    		font-size: 12px;
    		line-height: 15px;
    		padding: 0px 15px;
		}

		.no-faktur {
		    float: left;
		    width: 50%;
		    font-size: 12px;
		}

		.no-faktur p {
			position: relative;
    		top: 2px;
    		right: -555px;
		}

		.border-bottom {
		    float: left;
		    width: 100%;
		    border-bottom: 1px solid #333;
		    margin-top: 15px;
		}

		table {
			border-collapse: collapse;
			border-spacing: 0;
			width: 100%;
			border-bottom: 1px solid #333;
		}

		th {
			font-size: 12px;
			text-align: left;
			padding: 8px;
			border-bottom: 1px solid #333;
		}

		td {
			font-size: 12px;
			overflow: hidden;
			padding: 8px;
		}

		td p { text-align: justify; }

		.info-admin {
			float: left;
    		width: 98%;
    		text-align: right;
    		font-size: 12px;
    		line-height: 15px;
    		padding: 0px 15px;
    		margin: 15px 0px;
		}

		.alert {
		    width: 97%;
		    padding: 13px 0px 13px 13px;
		    background-color: #da6a5f;
		    border-radius: 3px;
		    color: #fff;
		    font-size: 14px;
		    text-align: left;
		    line-height: 20px;
		    margin: 15px;
		}

	</style>

</head>

<body>

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
				Tidak ditemukan data transaksi pembelian
			</div><!-- .alert -->

		<?php } else { ?>

			<?php $row1	= mysqli_fetch_array($sql1); ?>

			<div id="div1" class="a4">

				<div class="header">
					Struk <?php echo $row1["kolom_kode_penjualan"]; ?>
				</div>

				<div class="info-perusahaan">
					PT. CHICKEN MAJU MUNDUR KENA<br>
					Jl. Aria Putra No.02 Ciputat , Pamulang<br>
					Telp. 021-5558-2323<br>
					No. Faktur : <?php echo $row1["kolom_kode_penjualan"]; ?><br>
					Admin : <?php echo $session_kode_admin; ?><br>
					Tanggal : <?php echo format_tanggal($row1["kolom_tanggal_penjualan"]); ?>
				</div>

				<div class="border-bottom"></div>

				<table>

					<tr>
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
							<td><?php echo $row2["kolom_nama_menu"]; ?></td>
							<td>Rp. <?php echo format_nomor($row2["kolom_harga_menu"]); ?></td>
							<td><?php echo format_nomor($row2["kolom_jumlah_porsi"]); ?></td>
							<td>Rp. <?php echo format_nomor($total_harga_porsi); ?></td>
						</tr>

					<?php } ?>

				</table><!-- table -->

				<table>
					
					<tr>
						<td width="70%;"></td>
						<td>Total Porsi Menu : <?php echo format_nomor($jumlah_porsi); ?></td>
					</tr>

					<tr>
						<td></td>
						<td>Total Biaya Pembayaran : Rp. <?php echo format_nomor($total_pembayaran); ?></td>
					</tr>

					<tr>
						<td></td>
						<td>Jumlah Biaya Pembayaran : Rp. <?php echo format_nomor($row1["kolom_uang_tunai"]); ?></td>
					</tr>

					<?php $uang_kembalian = $row1["kolom_uang_tunai"] - $total_pembayaran; ?>

					<tr>
						<td></td>
						<td>Kembali Biaya Pembayaran : Rp. <?php echo format_nomor($uang_kembalian); ?></td>
					</tr>

				</table>

			</div>

			<button onclick="printContent('div1')">Print</button>

		<?php } ?>

	<?php } ?>

</body>
</html>

<?php

	mysqli_close($koneksi);
	ob_end_flush();

?>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        