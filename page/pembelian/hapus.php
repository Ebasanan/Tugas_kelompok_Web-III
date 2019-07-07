<div class="header">
			
	<h1>Pembelian</h1><!-- .main .header h1 -->

	<a href="<?php echo base_url."?folder=pembelian&file=tambah"; ?>" class="tambah">Kembali</a><!-- .main .header a -->

</div><!-- .main .header -->

<?php

	// mengecek nilai kode_item_barang data pada url
	$kode_item_barang	= isset($_GET["kode_item_barang"]) ? $_GET["kode_item_barang"] : false;

	// Jika kode item barang tidak ada dalam url browser
	if (!$kode_item_barang) {

		echo "<div class='alert'>";
		echo "Kamu belum memilih data item barang";
		echo "</div>";

	} # if !$kode_item_barang

	// Jika kode item barang ada dalam url browser
	else {

		// mengecek nilai kode item barang yang didapatkan pada url
		$sql1 	=	mysqli_query($koneksi,	"
												SELECT * FROM
													tabel_item_barang
												WHERE
													kolom_kode_item_barang='$kode_item_barang'
												AND
													kolom_kode_pembelian=''
											")
											
											or die("
												<div class='alert'>
													sql1 error<br>
													".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
												</div>
											");

		// jika nilai kode item barang yang didapatkan pada url tidak ada didalam tabel item barang
		if (mysqli_num_rows($sql1)==0) {

			echo "<div class='alert'>";
			echo "Kode ".$kode_item_barang." tidak ditemukan dalam tabel item barang";
			echo "</div>";

		} # if mysqli_num_rows $sql1==0

		// jika nilai kode item barang yang didapatkan pada url ada didalam item barang
		else {

			$sql2 	=	mysqli_query($koneksi,	"
													DELETE FROM
														tabel_item_barang
													WHERE
														kolom_kode_item_barang='$kode_item_barang'
												");

			// jika pernyataan benar
			if ($sql2==true) {

				echo 	"<script>";
				echo 	"
							if (confirm('Berhasil mengahapus data item barang')) {
								window.location.replace('".base_url."?folder=pembelian&file=tambah');
							}
						";
				echo 	"</script>";

			} # if $sql2==true

			else {

				echo 	"<div class='alert'>";
				echo 	"sql2 error<br>";
				echo 	"".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."";
				echo 	"</div>";

			} # else $sql2==false

		} # else mysqli_num_rows $sql1 > 0

	} # else $kode_item_barang

?>