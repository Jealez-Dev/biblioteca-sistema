
<?php 

$controllers=array(
	
	'Noticia'=>['ListarNoticia', 'IngresarNoticia', 'IngresarNoticia1', 'UpdateNoticia', 'UpdateNoticia1', 'DeleteNoticia'],
	'User'   =>['ListarUser', 'IngresarUser', 'IngresarUser2', 'UpdateUser', 'UpdateUser2', 'DeleteUser', 'DeleteUser1'],
	'Admin'  =>['ListarAdmin', 'IngresarAdmin', 'IngresarAdmin2', 'UpdateAdmin', 'UpdateAdmin2', 'DeleteAdmin', 'DeleteAdmin1'],
	'Lector' =>['ListarLector', 'IngresarLector', 'IngresarLector2', 'UpdateLector', 'UpdateLector2', 'DeleteLector', 'DeleteLector1'],
	'CatgDeUser' =>['ListarCatgDeUser', 'UpdateCatgDeUser', 'UpdateCatgDeUser2'],
	'Editorial' =>['ListarEditorial', 'IngresarEditorial', 'IngresarEditorial2', 'UpdateEditorial', 'UpdateEditorial2', 'DeleteEditorial', 'DeleteEditorial1'],
	'Autor'  =>['ListarAutor', 'IngresarAutor', 'IngresarAutor2', 'UpdateAutor', 'UpdateAutor2', 'DeleteAutor', 'DeleteAutor1'],
	// este arreglo ira creciendo a la medida que va creciendo las opciones de menu me mi sistema
);

if (@array_key_exists($controller, $controllers)) {
	
	if (in_array($action, $controllers[$controller])) {
		call($controller, $action);
	}
	else{
		call('Noticia','ListarNoticia');
	}		
}else{
	
		call('Noticia','ListarNoticia');
}

function call($controller, $action){
	

	require_once('controllers/'.$controller.'Controller.php');

	switch ($controller) {
		 
		case 'Noticia': 
			  $controller= new NoticiaController();
			  break;
		case 'User':
			  $controller= new UserController();
			  break;
		case 'Admin':
			  $controller= new AdminController();
			  break;
		case 'Lector':
			  $controller= new LectorController();
			  break;
		case 'CatgDeUser':
			  $controller= new CatgDeUserController();
			  break;
	    case 'Editorial':
			  $controller= new EditorialController();
			  break;
		case 'Autor':
			  $controller= new AutorController();
			  break;	  
			 // en este switche habran tantos case como listas del menu se tengan
	}
	
	$controller->{$action}();
}

?>

