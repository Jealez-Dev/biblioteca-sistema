<?php 

class TObrasModel
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
	

	public static function ListarTObras(){
		$sql = "SELECT * FROM t_obras ORDER BY ID asc";
		$result = TObrasModel::Get_Data($sql);
  		return $result;
	}

    // Para la insersión

	public static function BuscarUltimoTObras(){

		$sql = "SELECT (max(ID)) as identific FROM t_obras order BY ID asc";
		$result = TObrasModel::Get_Data($sql);
  		return $result;
	}

	public static function IngresarTObras2 ($ID, $Descripcion, $Prestar){

		$sql = "INSERT INTO t_obras (ID, Descripcion, Prestar) VALUES ('$ID', '$Descripcion', '$Prestar')";
		$result = TObrasModel::Update_Data($sql);
  		return $result;
	}

	// Para la actualización 

	public static function BuscarTObrasById($ID){
    	$sql = "SELECT * FROM t_obras WHERE ID = '$ID'";
		$result = TObrasModel::Get_Data($sql);
  		return $result;
	}

	public static function UpdateTObras2 ($ID, $Descripcion, $Prestar){

		$sql = "UPDATE t_obras SET Descripcion = '$Descripcion', Prestar = '$Prestar' WHERE ID = '$ID'";
		$result = TObrasModel::Update_Data($sql);
  		return $result;
	}

	// Para eliminar

	public static function DeleteTObras ($ID){
		$sql = "DELETE FROM t_obras WHERE ID = '$ID'";
		$result = TObrasModel::Update_Data($sql);
  		return $result;
	}

	// Para búsqueda AJAX (autocomplete)

	public static function BuscarTObrasByQuery($query){
		$sql = "SELECT * FROM t_obras WHERE Descripcion LIKE '%$query%' OR ID LIKE '%$query%' ORDER BY ID asc LIMIT 10";
		$result = TObrasModel::Get_Data($sql);
  		return $result;
	}
}
?>
