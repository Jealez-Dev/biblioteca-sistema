<?php 

class EditorialModel
{
	
	function __construct()
	{
	
	}

	// FUNCIONES GENERICAS PARA CONSULTAR Y ACTUALIZAR (INSERTAR, MODIFICAR Y ELIMINAR)

	public static function Get_Data($sql){
		include_once('core/Conectar.php');
		$conexion=Conectar::conexion();
		if(!$result = mysqli_query($conexion, $sql)) die();
		$conexion = Conectar::desconexion($conexion);
  		return $result;
	}

	public static function Update_Data($sql){
		include_once('core/Conectar.php');
		$conexion=Conectar::conexion();
		mysqli_autocommit($conexion, FALSE);
		$result = mysqli_query($conexion, $sql);

		if ($result == true) // la consulta fue exitosa 
		{   
			if (mysqli_affected_rows($conexion) == 0) // si no hizo la actualizacion
			{   mysqli_rollback($conexion);
				$result = false;
			}else   // si hizo la actualizacion
			{   mysqli_commit($conexion);
				$result = true;
		    }
		}

		$conexion = Conectar::desconexion($conexion);

	  	return $result;
	}

	
    // para el resto de las operaciones
	
	public static function ListarEditorial(){
		$sql_Editorial = "SELECT * FROM editorial ORDER BY ID asc";
		$result_Editorial = EditorialModel::Get_Data($sql_Editorial);
  		return $result_Editorial;
	}

    // Para la insersión

	public static function BuscarUltimaEditorial(){

		$sql_Editorial = "SELECT (max(ID)) as identific FROM editorial order BY ID asc";
		$result_Editorial = EditorialModel::Get_Data($sql_Editorial);
  		return $result_Editorial;
	}

	public static function IngresarEditorial2 ($id, $nombre, $sedeMatriz, $email, $telefono){

		$sql_Editorial = "INSERT INTO editorial (ID, Nombre, SedeMatriz, Email, Telefono) VALUES ('$id', '$nombre', '$sedeMatriz', '$email', '$telefono')";
		$result_Editorial = EditorialModel::Update_Data($sql_Editorial);
  		return $result_Editorial;
	}


	// Para la actualización 

		public static function BuscarEditorialById($id){
    	$sql_Editorial = "SELECT * FROM editorial WHERE ID = '$id'";
		$result_Editorial = EditorialModel::Get_Data($sql_Editorial);
  		return $result_Editorial;
	}

	public static function UpdateEditorial2 ($id, $nombre, $sedeMatriz, $email, $telefono){

		$sql_Editorial= "UPDATE editorial SET  Nombre = '$nombre', SedeMatriz = '$sedeMatriz', Email = '$email', Telefono = '$telefono' WHERE ID = '$id'";
		$result_Editorial = EditorialModel::Update_Data($sql_Editorial);
  		return $result_Editorial;
	}
	
	// Para eliminar

	public static function DeleteEditorial ($id){
		$sql_Editorial = "DELETE FROM editorial WHERE ID = '$id'";
		$result_Editorial = EditorialModel::Update_Data($sql_Editorial);
  		return $result_Editorial;
	}
}
?>