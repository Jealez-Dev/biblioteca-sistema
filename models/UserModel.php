<?php 

class UserModel
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
	

	public static function ListarUser(){
		$sql_noticia = "SELECT * FROM Usuario ORDER BY DNI asc";
		$result_noticia = UserModel::Get_Data($sql_noticia);
  		return $result_noticia;
	}

    // Para la insersión

	public static function BuscarUltimoUser(){

		$sql_noticia = "SELECT (max(DNI)) as identific FROM Usuario order BY DNI asc";
		$result_noticia = UserModel::Get_Data($sql_noticia);
  		return $result_noticia;
	}

	public static function IngresarUser2 ($DNI, $Nombre, $Apellido, $Edad, $Correo, $Num_Telefono){

		$sql_noticia = "INSERT INTO Usuario (DNI, Nombre, Apellido, Edad, Correo, Num_Telefono) VALUES ('$DNI', '$Nombre', '$Apellido', '$Edad', '$Correo', '$Num_Telefono')";
		$result_noticia = UserModel::Update_Data($sql_noticia);
  		return $result_noticia;
	}

	// Para la actualización 

	public static function BuscarUserById($DNI){
    	$sql_noticia = "SELECT * FROM Usuario WHERE DNI = $DNI";
		$result_noticia = UserModel::Get_Data($sql_noticia);
  		return $result_noticia;
	}

	public static function UpdateUser2 ($DNI, $Nombre, $Apellido, $Edad, $Correo, $Num_Telefono){

		$sql_noticia= "UPDATE Usuario SET DNI = '$DNI', Nombre = '$Nombre', Apellido = '$Apellido', Edad = '$Edad', Correo = '$Correo', Num_Telefono = '$Num_Telefono' WHERE DNI = $DNI";
		$result_noticia = UserModel::Update_Data($sql_noticia);
  		return $result_noticia;
	}

	
	// Para eliminar

	public static function DeleteUser ($DNI){
		$sql_noticia = "DELETE FROM Usuario WHERE DNI = $DNI";
		$result_noticia = UserModel::Update_Data($sql_noticia);
  		return $result_noticia;
	}

}

?>