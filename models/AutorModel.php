<?php 

class AutorModel
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
	

	public static function ListarAutor(){
		$sql_noticia = "SELECT * FROM autores ORDER BY Cutter asc";
		$result_noticia = AutorModel::Get_Data($sql_noticia);
  		return $result_noticia;
	}

    // Para la insersión

	public static function BuscarUltimoAutor(){

		$sql_noticia = "SELECT (max(Cutter)) as identific FROM autores order BY Cutter asc";
		$result_noticia = AutorModel::Get_Data($sql_noticia);
  		return $result_noticia;
	}

	public static function IngresarAutor2 ($Cutter, $Nombre, $Nacionalidad){

		$sql_noticia = "INSERT INTO autores (Cutter, Nombre, Nacionalidad) VALUES ('$Cutter', '$Nombre', '$Nacionalidad')";
		$result_noticia = AutorModel::Update_Data($sql_noticia);
  		return $result_noticia;
	}

	// Para la actualización 

	public static function BuscarAutorByCutter($Cutter){
    	$sql_noticia = "SELECT * FROM autores WHERE Cutter = '$Cutter'";
		$result_noticia = AutorModel::Get_Data($sql_noticia);
  		return $result_noticia;
	}

	public static function UpdateAutor2 ($Cutter, $Nombre, $Nacionalidad){

		$sql_noticia= "UPDATE autores SET Nombre = '$Nombre', Nacionalidad = '$Nacionalidad' WHERE Cutter = '$Cutter'";
		$result_noticia = AutorModel::Update_Data($sql_noticia);
  		return $result_noticia;
	}

	// Para eliminar

	public static function DeleteAutor ($Cutter){
		$sql_noticia = "DELETE FROM autores WHERE Cutter = '$Cutter'";
		$result_noticia = AutorModel::Update_Data($sql_noticia);
  		return $result_noticia;
	}

	// Para búsqueda AJAX (autocomplete)

	public static function BuscarAutorByQuery($query){
		$sql = "SELECT * FROM autores WHERE Nombre LIKE '%$query%' OR Cutter LIKE '%$query%' ORDER BY Cutter asc LIMIT 10";
		$result = AutorModel::Get_Data($sql);
  		return $result;
	}
 
}

?>