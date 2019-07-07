<div class="header">
			
	<h1>Agen</h1><!-- .main .header h1 -->

	<a href="<?php echo base_url."?folder=agen&file=index"; ?>" class="tambah">Kembali</a><!-- .main .header a -->

</div><!-- .main .header -->

<?php

	// mengecek nilai kode_agen data pada url
	$kode_agen	= isset($_GET["kode_agen"]) ? $_GET["kode_agen"] : false;

	// Jika kode agen tidak ada dalam url browser
	if (!$kode_agen) {

		echo "<div class='alert'>";
		echo "Kamu belum memilih data agen";
		echo "</div>";

	} # if !$kode_agen

	// Jika kode agen ada dalam url browser
	else {

		// mengecek nilai kode agen yang didapatkan pada url
		$sql1 	=	mysqli_query($koneksi,	"
												SELECT * FROM
													tabel_agen
												WHERE
													kolom_kode_agen='$kode_agen'
											")
											
											or die("
												<div class='alert'>
													sql1 error<br>
													".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
												</div>
											");

		// jika nilai kode agen yang didapatkan pada url tidak ada didalam tabel agen
		if (mysqli_num_rows($sql1)==0) {

			echo "<div class='alert'>";
			echo "Kode ".$kode_agen." tidak ditemukan dalam tabel agen";
			echo "</div>";

		} # if mysqli_num_rows $sql1==0

		// jika nilai kode agen yang didapatkan pada url ada didalam tabel agen
		else {

			$sql2 	=	mysqli_query($koneksi,	"
													DELETE FROM
														tabel_agen
													WHERE
														kolom_kode_agen='$kode_agen'
												");

			// jika pernyataan benar
			if ($sql2==true) {

				echo 	"<script>";
				echo 	"
							if (confirm('Berhasil mengahapus data agen')) {
								window.location.replace('".base_url."?folder=agen&file=index');
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

	} # else $kode_agen

?>