<?php 
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  ob_start();
  session_start();

  if (isset($_GET['controller']) && isset($_GET['action'])) {
    $controller = $_GET['controller'];
    $action = $_GET['action'];
  } else {
    // Si ya está logueado, vamos a Noticias, si no, el switch de abajo lo mandará a Login
    $controller = 'Obra';
    $action = 'ListarObra';
  }

  // Verificar si el usuario está logueado
  // O si está intentando acceder al controlador de Login
  if (!isset($_SESSION['User']) && $controller !== 'Login') {
      $controller = 'Login';
      $action = 'Index';
  }

  require_once('views/Layouts/layout.php'); 
 ?>
