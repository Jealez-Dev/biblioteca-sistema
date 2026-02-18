<?php 

class LectorModel
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
	

	public static function ListarLector(){
		$sql_noticia = "SELECT * FROM usuario_sin_acceso ORDER BY DNI_Usuario asc";
		$result_noticia = LectorModel::Get_Data($sql_noticia);
  		return $result_noticia;
	}

    // Para la insersión

	public static function BuscarUltimoLector(){

		$sql_noticia = "SELECT (max(DNI)) as identific FROM usuario_sin_acceso order BY DNI_Usuario asc";
		$result_noticia = LectorModel::Get_Data($sql_noticia);
  		return $result_noticia;
	}

	public static function IngresarLector2 ($DNI, $Carrera_Departamento, $ID_Catg_de_User_SA){

		$sql_noticia = "INSERT INTO usuario_sin_acceso (DNI_Usuario, Carrera_Departamento, ID_Catg_de_User_SA) VALUES ($DNI, '$Carrera_Departamento', '$ID_Catg_de_User_SA')";
		$result_noticia = LectorModel::Update_Data($sql_noticia);
  		return $result_noticia;
	}

	// Para la actualización 

	public static function BuscarLectorById($DNI){
    	$sql_noticia = "SELECT * FROM usuario_sin_acceso WHERE DNI_Usuario = $DNI";
		$result_noticia = LectorModel::Get_Data($sql_noticia);
  		return $result_noticia;
	}

	public static function UpdateLector2 ($DNI, $Carrera_Departamento, $ID_Catg_de_User_SA){

		$sql_noticia= "UPDATE usuario_sin_acceso SET DNI_Usuario = '$DNI', Carrera_Departamento = '$Carrera_Departamento', ID_Catg_de_User_SA = '$ID_Catg_de_User_SA' WHERE DNI_Usuario = $DNI";
		$result_noticia = LectorModel::Update_Data($sql_noticia);
  		return $result_noticia;
	}

	
	// Para eliminar

	public static function DeleteLector ($DNI){
		$sql_noticia = "DELETE FROM usuario_sin_acceso WHERE DNI_Usuario = $DNI";
		$result_noticia = LectorModel::Update_Data($sql_noticia);
  		return $result_noticia;
	}

}

?>