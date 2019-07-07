<?php

	session_start();
	ob_start();

	include_once ("../../config/koneksi.php");
	include_once ("../../config/function.php");

	$kode_pembelian		= 	isset($_GET["kode_pembelian"]) ? $_GET["kode_pembelian"] : false;
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

	<title>Faktur <?php echo $kode_pembelian; ?></title>

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

			<div id="div1" class="a4">

				<div class="header">
					Faktur Pembelian Barang <?php echo $row1["kolom_kode_pembelian"]; ?>
				</div>

				<div class="info-perusahaan">
					PT. CHICKEN MAJU MUNDUR KENA<br>
					Jl. Aria Putra No.02 Ciputat , Pamulang<br>
					Telp. 021-5558-2323
				</div>

				<div class="no-faktur">
					<p>No. Faktur : <?php echo $row1["kolom_kode_pembelian"]; ?></p>
				</div>

				<div class="border-bottom"></div>

				<table>
					
					<tr>
						<th>Kode Item Barang</th>
						<th>Nama Agen</th>
						<th>Nama Barang</th>
						<th>Harga Barang</th>
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

				</table>

				<table>
					
					<tr>
						<td width="70%;"></td>
						<td>Total Jumlah Menu : <?php echo format_nomor($jumlah_barang); ?></td>
					</tr>

					<tr>
						<td></td>
						<td>Total Biaya Pembelian : Rp. <?php echo format_nomor($biaya_pembelian); ?></td>
					</tr>

				</table>

				<div class="info-admin">

					Tangerang Selatan , <?php echo format_tanggal($row1["kolom_tanggal_pembelian"]); ?>
					<br><br><br><br><br><br><br>
					( <?php echo $row1["kolom_kode_admin"]." - @".$row1["kolom_username"]; ?> )

				</div>

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