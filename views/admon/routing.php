
<?php 

$controllers=array(
	
	'Noticia'=>['ListarNoticia', 'IngresarNoticia', 'IngresarNoticia1', 'UpdateNoticia', 'UpdateNoticia1', 'DeleteNoticia'],
	'User'   =>['ListarUser', 'IngresarUser', 'IngresarUser2', 'UpdateUser', 'UpdateUser2', 'DeleteUser', 'DeleteUser1'],
	'Admin'  =>['ListarAdmin', 'IngresarAdmin', 'IngresarAdmin2', 'UpdateAdmin', 'UpdateAdmin2', 'DeleteAdmin', 'DeleteAdmin1'],
	'Lector' =>['ListarLector', 'IngresarLector', 'IngresarLector2', 'UpdateLector', 'UpdateLector2', 'DeleteLector', 'DeleteLector1'],
	'CatgDeUser' =>['ListarCatgDeUser', 'UpdateCatgDeUser', 'UpdateCatgDeUser2'],
	'Editorial' =>['ListarEditorial', 'IngresarEditorial', 'IngresarEditorial2', 'UpdateEditorial', 'UpdateEditorial2', 'DeleteEditorial', 'DeleteEditorial1', 'BuscarEditorialJson'],
	'Autor'  =>['ListarAutor', 'IngresarAutor', 'IngresarAutor2', 'UpdateAutor', 'UpdateAutor2', 'DeleteAutor', 'DeleteAutor1', 'BuscarAutorJson'],
	'Cdd'    =>['ListarCdd', 'IngresarCdd', 'IngresarCdd2', 'UpdateCdd', 'UpdateCdd2', 'DeleteCdd', 'BuscarCddJson'],
	'TObras' =>['ListarTObras', 'IngresarTObras', 'IngresarTObras2', 'UpdateTObras', 'UpdateTObras2', 'DeleteTObras', 'BuscarTObrasJson'],
	'Obra'   =>['ListarObra', 'IngresarObra', 'IngresarObra2', 'UpdateObra', 'UpdateObra2', 'DeleteObra', 'GuardarEstadoObra'],
	'Ejemplar' => ['ListarEjemplar', 'IngresarEjemplar', 'IngresarEjemplar2', 'UpdateEjemplar', 'UpdateEjemplar2', 'DeleteEjemplar'],
	'Prestamo' => ['ListarPrestamo', 'IngresarPrestamo', 'IngresarPrestamo2', 'UpdatePrestamo', 'UpdatePrestamo2', 'DeletePrestamo'],
	'TSoporte' => ['ListarTSoporte'],
	'Estado'   => ['ListarEstado'],
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
		case 'Cdd':
			  $controller= new CddController();
			  break;
		case 'TObras':
			  $controller= new TObrasController();
			  break;
		case 'Obra':
			  $controller= new ObraController();
			  break;
		case 'Ejemplar':
			  $controller= new EjemplarController();
			  break;
		case 'Prestamo':
			  $controller= new PrestamoController();
			  break;
		case 'TSoporte':
			  $controller= new TSoporteController();
			  break;
		case 'Estado':
			  $controller= new EstadoController();
			  break;
			 // en este switche habran tantos case como listas del menu se tengan
	}
	
	$controller->{$action}();
}

?>

