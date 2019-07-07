<div class="header">
			
	<h1>Penjualan</h1><!-- .main .header h1 -->

	<a href="<?php echo base_url."?folder=penjualan&file=tambah"; ?>" class="tambah">Kembali</a><!-- .main .header a -->

</div><!-- .main .header -->

<?php

	// mengecek nilai kode_item_menu data pada url
	$kode_item_menu	= isset($_GET["kode_item_menu"]) ? $_GET["kode_item_menu"] : false;

	// Jika kode item menu tidak ada dalam url browser
	if (!$kode_item_menu) {

		echo "<div class='alert'>";
		echo "Kamu belum memilih data item menu";
		echo "</div>";

	} # if !$kode_item_menu

	// Jika kode item menu ada dalam url browser
	else {

		// mengecek nilai kode item menu yang didapatkan pada url
		$sql1 	=	mysqli_query($koneksi,	"
												SELECT * FROM
													tabel_item_menu
												WHERE
													kolom_kode_item_menu='$kode_item_menu'
												AND
													kolom_kode_penjualan=''
											")
											
											or die("
												<div class='alert'>
													sql1 error<br>
													".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
												</div>
											");

		// jika nilai kode item menu yang didapatkan pada url tidak ada didalam tabel item menu
		if (mysqli_num_rows($sql1)==0) {

			echo "<div class='alert'>";
			echo "Kode ".$tabel_item_menu." tidak ditemukan dalam tabel item menu";
			echo "</div>";

		} # if mysqli_num_rows $sql1==0

		// jika nilai kode item menu yang didapatkan pada url ada didalam item menu
		else {

			$sql2 	=	mysqli_query($koneksi,	"
													DELETE FROM
														tabel_item_menu
													WHERE
														kolom_kode_item_menu='$kode_item_menu'
												");

			// jika pernyataan benar
			if ($sql2==true) {

				echo 	"<script>";
				echo 	"
							if (confirm('Berhasil mengahapus data item menu')) {
								window.location.replace('".base_url."?folder=penjualan&file=tambah');
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

	} # else $kode_item_menu

?>