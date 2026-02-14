<!doctype html>
<html lang="en">
  <head>
  
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- , shrink-to-fit=no este pedazo lo quite cuando introduje el colapse de los divs-->
    <title>Sistema de Telemedicina </title>
    <link rel="icon" type="image/png" href="..\ejercicio\imagenes\LogoPaginaTelemedicina.gif">
    <link rel="shortcut icon" href="..\ejercicio\imagenes\LogoPaginaTelemedicina.gif">
    
    <!-- Bootstrap CSS -->
  	<link rel="stylesheet" href="../ejercicio/css/bootstrap.min.css";>   <!-- Bootstrap carga los componentes del css desde directorio-->
	<link rel="stylesheet" href="../ejercicio/css/navbar.css";>   <!-- Bootstrap carga los componentes del css desde directorio-->
	<link rel="stylesheet" href="../ejercicio/css/menu_submenu.css";>
	
	<!-- jQuery library -->

	<script src="../ejercicio/js/jquery.min.js"></script>

	<!--datatables-->
  	<link rel="stylesheet" type="text/css" href="../ejercicio/css/datatables.min.css" />
  	<script type="text/javascript" src="../ejercicio/js/datatables.min.js"></script>
  	<script type="text/javascript" src="../ejercicio/js/datatable.js"></script>
  	<!--datatables-->

  </head>

<body>

<header>
	<?php 
	   	require_once('../ejercicio/views/Layouts/banner.php');
    	require_once('../ejercicio/views/Layouts/header.php');
	?>	
</header>

<section>	
	<div class="container">
	<?php
	 	// carga el archivo routing.php para direccionar a la página .php que se incrustará entre la header y el footer
		require_once('../ejercicio/views/admon/routing.php');	
	?>
  	</div>
</section>

		    <!-- Optional JavaScript -->
		    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
		    <script src="../ejercicio/js/jquery-3.3.1.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
		    <script src="../ejercicio/js/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		    <script src="../ejercicio/js/bootstrap.min.js"></script>

<footer>
	<?php 
		require_once('../ejercicio/views/Layouts/footer.php');
	?>
</footer>
</body>

</html>

