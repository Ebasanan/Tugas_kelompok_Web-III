<div class="header">
			
	<h1>Kategori</h1><!-- .main .header h1 -->

	<a href="<?php echo base_url."?folder=kategori&file=index"; ?>" class="tambah">Kembali</a><!-- .main .header a -->

</div><!-- .main .header -->

<?php

	// mengecek nilai kode_kategori data pada url
	$kode_kategori = isset($_GET["kode_kategori"]) ? $_GET["kode_kategori"] : false;

	// Jika kode kategori tidak ada dalam url browser
	if (!$kode_kategori) {

		echo "<div class='alert'>";
		echo "Kamu belum memilih data kategori";
		echo "</div>";

	} # if !$kode_kategori

	// Jika kode kategori ada dalam url browser
	else {

		// mengecek nilai kode kategori yang didapatkan pada url
		$sql1 	=	mysqli_query($koneksi,	"
												SELECT * FROM
													tabel_kategori
												WHERE
													kolom_kode_kategori='$kode_kategori'
											")
											
											or die("
												<div class='alert'>
													sql1 error<br>
													".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
												</div>
											");

		// jika nilai kode kategori yang didapatkan pada url tidak ada didalam tabel kategori
		if (mysqli_num_rows($sql1)==0) {

			echo "<div class='alert'>";
			echo "Kode ".$kode_kategori." tidak ditemukan dalam tabel kategori";
			echo "</div>";

		} # if mysqli_num_rows $sql1==0

		// jika nilai kode kategori yang didapatkan pada url ada didalam tabel kategori
		else {

			$sql2 	=	mysqli_query($koneksi,	"
													DELETE FROM
														tabel_kategori
													WHERE
														kolom_kode_kategori='$kode_kategori'
												");

			// jika pernyataan benar
			if ($sql2==true) {

				echo 	"<script>";
				echo 	"
							if (confirm('Berhasil mengahapus data kategori')) {
								window.location.replace('".base_url."?folder=kategori&file=index');
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

	} # else $kode_kategori

?>