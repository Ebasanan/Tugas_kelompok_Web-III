<div class="header">
			
	<h1>Admin</h1><!-- .main .header h1 -->

	<a href="<?php echo base_url."?folder=admin&file=index"; ?>" class="tambah">Kembali</a><!-- .main .header a -->

</div><!-- .main .header -->

<?php

	// mengecek nilai kode_admin data pada url
	$kode_admin	= isset($_GET["kode_admin"]) ? $_GET["kode_admin"] : false;

	// Jika kode admin tidak ada dalam url browser
	if (!$kode_admin) {

		echo "<div class='alert'>";
		echo "Kamu belum memilih data admin";
		echo "</div>";

	} # if !$kode_admin

	// Jika kode admin ada dalam url browser
	else {

		// mengecek nilai kode admin yang didapatkan pada url
		$sql1 	=	mysqli_query($koneksi,	"
												SELECT * FROM
													tabel_admin
												WHERE
													kolom_kode_admin='$kode_admin'
											")
											
											or die("
												<div class='alert'>
													sql1 error<br>
													".mysqli_errno($koneksi)." - ".mysqli_error($koneksi)."
												</div>
											");

		// jika nilai kode admin yang didapatkan pada url tidak ada didalam tabel admin
		if (mysqli_num_rows($sql1)==0) {

			echo "<div class='alert'>";
			echo "Kode ".$kode_admin." tidak ditemukan dalam tabel admin";
			echo "</div>";

		} # if mysqli_num_rows $sql1==0

		// jika nilai kode admin yang didapatkan pada url ada didalam tabel admin
		else {

			$sql2 	=	mysqli_query($koneksi,	"
													DELETE FROM
														tabel_admin
													WHERE
														kolom_kode_admin='$kode_admin'
												");

			// jika pernyataan benar
			if ($sql2==true) {

				echo 	"<script>";
				echo 	"
							if (confirm('Berhasil mengahapus data admin')) {
								window.location.replace('".base_url."?folder=admin&file=index');
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

	} # else $kode_admin

?>