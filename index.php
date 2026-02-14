<?php 
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  session_start();
  $_SESSION['User'] = "Suha-Zaba";
  $_SESSION['Nivel'] = "Doctor";

  if (isset($_GET['controller'])&&isset($_GET['action'])) {
    $controller=$_GET['controller'];
    $action=$_GET['action'];
    
  }else{
     $controller = 'Noticias';
     $action = 'ListarNoticias';
  }
  require_once('views/Layouts/layout.php'); 
 ?>

 







  


 

   



