<!doctype html>
<html lang="en">
  <head>
  
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- , shrink-to-fit=no este pedazo lo quite cuando introduje el colapse de los divs-->
    <title>Biblioteca Sonia Quijada </title>
    <link rel="icon" type="image/png" href="..\biblioteca-sistema\imagenes\LogoPaginaTelemedicina.gif">
    <link rel="shortcut icon" href="..\biblioteca-sistema\imagenes\LogoPaginaTelemedicina.gif">
    
    <!-- Bootstrap CSS -->
  	<link rel="stylesheet" href="../biblioteca-sistema/css/bootstrap.min.css";>   <!-- Bootstrap carga los componentes del css desde directorio-->
	<link rel="stylesheet" href="../biblioteca-sistema/css/navbar.css";>   <!-- Bootstrap carga los componentes del css desde directorio-->
	<link rel="stylesheet" href="../biblioteca-sistema/css/menu_submenu.css";>
	
	<!-- jQuery library -->

	<script src="../biblioteca-sistema/js/jquery.min.js"></script>

  </head>

<body>

<header>
	<?php 
	   	require_once('../biblioteca-sistema/views/Layouts/banner.php');
    	require_once('../biblioteca-sistema/views/Layouts/header.php');
	?>	
</header>

<section>	
	<div class="container">
	<?php
	 	// carga el archivo routing.php para direccionar a la página .php que se incrustará entre la header y el footer
		require_once('../biblioteca-sistema/views/admon/routing.php');	
	?>
  	</div>
</section>

		    <!-- Optional JavaScript -->
		    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
		    <script src="../biblioteca-sistema/js/jquery-3.3.1.min.js"></script>
		    <script src="../biblioteca-sistema/js/popper.min.js"></script>
		    <script src="../biblioteca-sistema/js/bootstrap.min.js"></script>

			<!--datatables-->
			<link rel="stylesheet" type="text/css" href="../biblioteca-sistema/css/datatables.min.css" />
			<script type="text/javascript" src="../biblioteca-sistema/js/datatables.min.js"></script>
			<script type="text/javascript" src="../biblioteca-sistema/js/datatable.js"></script>
			<!--datatables-->

<footer>
	<?php 
		require_once('../biblioteca-sistema/views/Layouts/footer.php');
	?>
</footer>
</body>

</html>

