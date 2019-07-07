<a href="<?php echo base_url; ?>" id="logo">

	<img src="<?php echo base_url."image/chicken.svg"; ?>"><!-- .navbar #logo img -->

	<div id="text-logo">Chicken</div><!-- .navbar #logo #text-logo -->

</a><!-- .navbar #logo -->

<ul>

	<!-- Jika level admin adalah admin maka kita akan memberika beberapa akses tertentu -->
	<?php if ($session_level=="admin") { ?>

		<li>

			<a <?php if ($folder=="admin") { echo "class='active'"; } ?> href="<?php echo base_url."?folder=admin&file=index"; ?>">
				Admin
			</a><!-- .navbar li a -->

		</li><!-- .navbar li -->

		<li>

			<a <?php if ($folder=="agen") { echo "class='active'"; } ?> href="<?php echo base_url."?folder=agen&file=index"; ?>">
				Agen
			</a><!-- .navbar li a -->

		</li><!-- .navbar li -->

		<li>

			<a <?php if ($folder=="satuan") { echo "class='active'"; } ?> href="<?php echo base_url."?folder=satuan&file=index"; ?>">
				Satuan
			</a><!-- .navbar li a -->

		</li><!-- .navbar li -->

		<li>

			<a <?php if ($folder=="kategori") { echo "class='active'"; } ?> href="<?php echo base_url."?folder=kategori&file=index"; ?>">
				Kategori
			</a><!-- .navbar li a -->

		</li><!-- .navbar li -->

		<li>

			<a <?php if ($folder=="menu") { echo "class='active'"; } ?> href="<?php echo base_url."?folder=menu&file=index"; ?>">
				Menu
			</a><!-- .navbar li a -->

		</li><!-- .navbar li -->

	<?php } ?>

	<li>

		<a <?php if ($folder=="pembelian") { echo "class='active'"; } ?> href="<?php echo base_url."?folder=pembelian&file=index"; ?>">
			Pembelian
		</a><!-- .navbar li a -->

	</li><!-- .navbar li -->

	<li>

		<a <?php if ($folder=="penjualan") { echo "class='active'"; } ?> href="<?php echo base_url."?folder=penjualan&file=index"; ?>">
			Penjualan
		</a><!-- .navbar li a -->

	</li><!-- .navbar li -->

</ul><!-- .navbar ul -->

<a href="logout.php" id="logout">
	Logout (@<?php echo $session_username; ?>)
</a><!-- .navbar #logout -->