<?php 

class CddModel
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
	

	public static function ListarCdd(){
		$sql = "SELECT * FROM cdd ORDER BY Codigo asc";
		$result = CddModel::Get_Data($sql);
  		return $result;
	}

    // Para la insersión

	public static function BuscarUltimoCdd(){

		$sql = "SELECT (max(Codigo)) as identific FROM cdd order BY Codigo asc";
		$result = CddModel::Get_Data($sql);
  		return $result;
	}

	public static function IngresarCdd2 ($Codigo, $Descripcion){

		$sql = "INSERT INTO cdd (Codigo, Descripcion) VALUES ('$Codigo', '$Descripcion')";
		$result = CddModel::Update_Data($sql);
  		return $result;
	}

	// Para la actualización 

	public static function BuscarCddById($Codigo){
    	$sql = "SELECT * FROM cdd WHERE Codigo = '$Codigo'";
		$result = CddModel::Get_Data($sql);
  		return $result;
	}

	public static function UpdateCdd2 ($Codigo, $Descripcion){

		$sql = "UPDATE cdd SET Descripcion = '$Descripcion' WHERE Codigo = '$Codigo'";
		$result = CddModel::Update_Data($sql);
  		return $result;
	}

	// Para eliminar

	public static function DeleteCdd ($Codigo){
		$sql = "DELETE FROM cdd WHERE Codigo = '$Codigo'";
		$result = CddModel::Update_Data($sql);
  		return $result;
	}

	// Para búsqueda AJAX (autocomplete)

	public static function BuscarCddByQuery($query){
		$sql = "SELECT * FROM cdd WHERE Codigo LIKE '%$query%' OR Descripcion LIKE '%$query%' ORDER BY Codigo asc LIMIT 10";
		$result = CddModel::Get_Data($sql);
  		return $result;
	}
}
?>
