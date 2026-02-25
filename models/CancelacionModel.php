<?php

class CancelacionModel
{
	function __construct()
	{
	}

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

		if ($result == true)
		{   
			if (mysqli_affected_rows($conexion) == 0)
			{   mysqli_rollback($conexion);
				$result = false;
			}else
			{   mysqli_commit($conexion);
				$result = true;
			}
		}

		$conexion = Conectar::desconexion($conexion);
	  	return $result;
	}

	// ==================== LISTAR ====================
	public static function ListarCancelacion(){
		$sql = "SELECT * FROM cancelacion ORDER BY ID DESC";
		$result = CancelacionModel::Get_Data($sql);
  		return $result;
	}

	// ==================== BUSCAR POR ID ====================
	public static function BuscarCancelacionById($id){
    	$sql = "SELECT * FROM cancelacion WHERE ID = '$id'";
		$result = CancelacionModel::Get_Data($sql);
  		return $result;
	}

	// ==================== INSERTAR ====================
	public static function IngresarCancelacion($username, $fCancelacion, $prestamoData){
		$sql = "INSERT INTO cancelacion (Username, F_cancelacion, Prestamo) 
		        VALUES ('$username', '$fCancelacion', '$prestamoData')";
		$result = CancelacionModel::Update_Data($sql);
  		return $result;
	}

	// ==================== ACTUALIZAR ====================
	public static function UpdateCancelacion($id, $username, $fCancelacion, $prestamoData){
		$sql = "UPDATE cancelacion SET Username = '$username', F_cancelacion = '$fCancelacion', Prestamo = '$prestamoData' 
		        WHERE ID = '$id'";
		$result = CancelacionModel::Update_Data($sql);
  		return $result;
	}

	// ==================== ELIMINAR ====================
	public static function DeleteCancelacion($id){
		$sql = "DELETE FROM cancelacion WHERE ID = '$id'";
		$result = CancelacionModel::Update_Data($sql);
  		return $result;
	}
}
?>
