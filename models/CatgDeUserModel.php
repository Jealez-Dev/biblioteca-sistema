<?php 

class CatgDeUserModel
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
	

	public static function ListarCatgDeUser(){
		$sql_noticia = "SELECT * FROM Catg_de_User_SA ORDER BY ID asc";
		$result_noticia = CatgDeUserModel::Get_Data($sql_noticia);
  		return $result_noticia;
	}

    // Para la insersión

	public static function BuscarUltimoCatgDeUser(){

		$sql_noticia = "SELECT (max(ID)) as identific FROM Catg_de_User_SA order BY ID asc";
		$result_noticia = CatgDeUserModel::Get_Data($sql_noticia);
  		return $result_noticia;
	}

	// Para la actualización 

	public static function BuscarCatgDeUserById($ID){
    	$sql_noticia = "SELECT * FROM Catg_de_User_SA WHERE ID = $ID";
		$result_noticia = CatgDeUserModel::Get_Data($sql_noticia);
  		return $result_noticia;
	}

	public static function UpdateCatgDeUser2 ($ID, $N_de_dias, $N_de_ejemplares){

		$sql_noticia= "UPDATE Catg_de_User_SA SET N_de_dias = '$N_de_dias', N_de_ejemplares = '$N_de_ejemplares' WHERE ID = $ID";
		$result_noticia = CatgDeUserModel::Update_Data($sql_noticia);
  		return $result_noticia;
	}
}
?>