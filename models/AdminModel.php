<?php 

class AdminModel
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
	

	public static function ListarAdmin(){
		$sql_noticia = "SELECT * FROM admin ORDER BY DNI_Usuario asc";
		$result_noticia = AdminModel::Get_Data($sql_noticia);
  		return $result_noticia;
	}

    // Para la insersión

	public static function BuscarUltimoAdmin(){

		$sql_noticia = "SELECT (max(DNI)) as identific FROM admin order BY DNI_Usuario asc";
		$result_noticia = AdminModel::Get_Data($sql_noticia);
  		return $result_noticia;
	}

	public static function IngresarAdmin2 ($DNI, $Username, $Password){

		$sql_noticia = "INSERT INTO admin (DNI_Usuario, Username, Password) VALUES ($DNI, '$Username', '$Password')";
		$result_noticia = AdminModel::Update_Data($sql_noticia);
  		return $result_noticia;
	}

	// Para la actualización 

	public static function BuscarAdminById($DNI){
    	$sql_noticia = "SELECT * FROM admin WHERE DNI_Usuario = $DNI";
		$result_noticia = AdminModel::Get_Data($sql_noticia);
  		return $result_noticia;
	}

	public static function UpdateAdmin2 ($DNI, $Username, $Password){

		$sql_noticia= "UPDATE admin SET DNI_Usuario = '$DNI', Username = '$Username', Password = '$Password' WHERE DNI_Usuario = $DNI";
		$result_noticia = AdminModel::Update_Data($sql_noticia);
  		return $result_noticia;
	}

	
	// Para eliminar

	public static function DeleteAdmin ($DNI_Usuario){
		include_once('models/CascadeModel.php');
		return CascadeModel::RecursiveDelete('admin', ['DNI_Usuario' => $DNI_Usuario]);
	}

	public static function Login($Username, $Password){
		$sql = "SELECT * FROM admin WHERE Username = '$Username'";
		$result = AdminModel::Get_Data($sql);
		
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			if ($row['Password'] === $Password) {
				return ['status' => 'success', 'user' => $row];
			} else {
				return ['status' => 'incorrect_password'];
			}
		} else {
			return ['status' => 'invalid_user'];
		}
	}

}
?>