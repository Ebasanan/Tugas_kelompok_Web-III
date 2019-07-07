<div class="header">
			
	<h1>Satuan</h1><!-- .main .header h1 -->

	<a href="<?php echo base_url."?folder=satuan&file=index"; ?>" class="tambah">Kembali</a><!-- .main .header a -->

</div><!-- .main .header -->

<?php

	// mengecek nilai kode_satuan data pada url
	$kode_satuan = isset($_GET["kode_satuan"]) ? $_GET["kode_satuan"] : false;

	// Jika kode satuan tidak ada dalam url browser
	if (!$kode_satuan) {

		echo "<div class='alert'>";
		echo "Kamu belum memilih data satuan";
		echo "</div>";

	} # if !$kode_satuan

	// Jika kode satuan ada dalam url browser
	else {

		// mengecek nilai kode satuan yang didapatkan pada url
		$sql1 	=	mysqli_query($koneksi,	"
												SELECT * FROM
													tabel_satuan
												WHERE
													kolom_kode_satuan='$kode_satuan'
											")
											
											or die("
												<div class='alert'>
													sql1 error<br>
													".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
												</div>
											");

		// jika nilai kode satuan yang didapatkan pada url tidak ada didalam tabel satuan
		if (mysqli_num_rows($sql1)==0) {

			echo "<div class='alert'>";
			echo "Kode ".$kode_satuan." tidak ditemukan dalam tabel satuan";
			echo "</div>";

		} # if mysqli_num_rows $sql1==0

		// jika nilai kode satuan yang didapatkan pada url ada didalam tabel satuan
		else {

			$sql2 	=	mysqli_query($koneksi,	"
													DELETE FROM
														tabel_satuan
													WHERE
														kolom_kode_satuan='$kode_satuan'
												");

			// jika pernyataan benar
			if ($sql2==true) {

				echo 	"<script>";
				echo 	"
							if (confirm('Berhasil mengahapus data satuan')) {
								window.location.replace('".base_url."?folder=satuan&file=index');
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

	} # else $kode_satuan

?>