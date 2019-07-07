<div class="header">
			
	<h1>Menu</h1><!-- .main .header h1 -->

	<a href="<?php echo base_url."?folder=menu&file=index"; ?>" class="tambah">Kembali</a><!-- .main .header a -->

</div><!-- .main .header -->

<?php

	// mengecek nilai kode_menu data pada url
	$kode_menu	= isset($_GET["kode_menu"]) ? $_GET["kode_menu"] : false;

	// Jika kode menu tidak ada dalam url browser
	if (!$kode_menu) {

		echo "<div class='alert'>";
		echo "Kamu belum memilih data menu";
		echo "</div>";

	} # if !$kode_menu

	// Jika kode menu ada dalam url browser
	else {

		// mengecek nilai kode menu yang didapatkan pada url
		$sql1 	=	mysqli_query($koneksi,	"
												SELECT * FROM
													tabel_menu
												WHERE
													kolom_kode_menu='$kode_menu'
											")
											
											or die("
												<div class='alert'>
													sql1 error<br>
													".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
												</div>
											");

		// jika nilai kode menu yang didapatkan pada url tidak ada didalam tabel menu
		if (mysqli_num_rows($sql1)==0) {

			echo "<div class='alert'>";
			echo "Kode ".$kode_menu." tidak ditemukan dalam tabel menu";
			echo "</div>";

		} # if mysqli_num_rows $sql1==0

		// jika nilai kode menu yang didapatkan pada url ada didalam tabel menu
		else {

			$sql2 	=	mysqli_query($koneksi,	"
													DELETE FROM
														tabel_menu
													WHERE
														kolom_kode_menu='$kode_menu'
												");

			// jika pernyataan benar
			if ($sql2==true) {

				echo 	"<script>";
				echo 	"
							if (confirm('Berhasil mengahapus data menu')) {
								window.location.replace('".base_url."?folder=menu&file=index');
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

	} # else $kode_menu

?>