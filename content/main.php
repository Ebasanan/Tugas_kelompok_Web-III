<div class="container">

	<img src="image/chicken-border.svg" alt="Logo Chicken"><!-- .main .container img -->

	<h1>Selamat Datang</h1>

</div><!-- .main .container -->

<!-- Jika admin sudah login dan ingin mengakses halaman login maka dia harus logout -->
<?php if ($proteksi=="akses") { ?>

	<script>
		alert("Silahkan logout untuk mengakses halaman login");
	</script>

<?php } ?>