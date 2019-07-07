<?php

	define("base_url", "http://localhost/chicken/");

	// echo base_url;

	function kode_otomatis ($kode_terakhir, $inisial, $panjang_kode=0) {

		$kode_baru 	=	intval(substr($kode_terakhir, strlen($inisial))) + 1;
		$hasil_kode	=	str_pad($kode_baru, $panjang_kode, "0", STR_PAD_LEFT);
		$susun_kode =	$inisial . $hasil_kode;
		return $susun_kode;

	} # function kode_otomatis

	//$kode_sekarang 	=	"KODE00";
	//$tampil_kode 	=	kode_otomatis($kode_sekarang, 'KODE', 2);
	//echo $tampil_kode;

	function format_nomor ($nilai = 0) {

		$string 	=	number_format($nilai, 0, ".", ".");
		return $string;

	} # function format_nomor

	//$rupiah = 10000000;
	//echo "Rp. ".format_nomor($rupiah);

	function format_tanggal ($date) {

		$tahun 			=	substr($date, 0, 4);
		$bulan 			=	substr($date, 5, 2);
		$tanggal 		=	substr($date, 8, 2);
		$susun_tanggal 	=	$tanggal."-".$bulan."-".$tahun;
		return($susun_tanggal);

	} # function format_tanggal

	//$tanggal_sekarang 	=	date('Y-m-d');
	//echo format_tanggal($tanggal_sekarang);

	function pagination($sqlPagination, $data_per_halaman, $pagination, $url) {

		$total_data 			=	mysqli_num_rows($sqlPagination);
		$total_halaman 			=	ceil($total_data / $data_per_halaman);
		$batas_nomor 			=	2;
		$batas_maksimal_nomor	=	5;
		$mulai_nomor 			=	1;
		$batas_akhir_nomor 		=	$total_halaman;

		echo 	"<div class='pagination'>";

		if ($pagination > 1) {

			$sebelumnya =	$pagination - 1;

			echo 	"
						<a href='".base_url."$url&pagination=$sebelumnya'>Sebelumnya</a>
					";

		} # if ($pagination > 1)

		if ($total_halaman >= $batas_maksimal_nomor) {

			if ($pagination > $batas_nomor) {

				$mulai_nomor	=	$pagination - ($batas_nomor - 1);

			} # if ($pagination > $batas_nomor)

			$batas_akhir_nomor	=	($mulai_nomor - 1) + $batas_maksimal_nomor;

			if ($batas_akhir_nomor > $total_halaman) {

				$batas_akhir_nomor	=	$total_halaman;

			} # if ($batas_akhir_nomor > $total_halaman)

		} # if ($total_halaman >= $batas_maksimal_nomor)

		for ($i=$mulai_nomor; $i<=$batas_akhir_nomor; $i++) { 

			if ($pagination==$i) {

				echo 	"
							<a class='active'>
								$i
							</a>
						";

			} # if ($pagination==$i)

			else {

				echo 	"
							<a href='".base_url."$url&pagination=$i'>
								$i
							</a>
						";

			} # else ($pagination==$i)

		} # for ($i=$mulai_nomor; $i<=$batas_akhir_nomor; $i++)

		if ($pagination < $total_halaman) {

			$selanjutnya	=	$pagination + 1;

			echo 	"
						<a href='".base_url."$url&pagination=$selanjutnya'>Selanjutnya</a>
					";

		} # if ($pagination < $total_halaman)

		echo 	"</div>";

	} # function pagination

?>