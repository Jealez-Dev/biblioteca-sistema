<?php

class RenovacionModel
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
	public static function ListarRenovacion(){
		$sql = "SELECT * FROM renovacion ORDER BY ID DESC";
		$result = RenovacionModel::Get_Data($sql);
  		return $result;
	}

	// ==================== BUSCAR POR ID ====================
	public static function BuscarRenovacionById($id){
    	$sql = "SELECT * FROM renovacion WHERE ID = '$id'";
		$result = RenovacionModel::Get_Data($sql);
  		return $result;
	}

	// ==================== INSERTAR ====================
	public static function IngresarRenovacion($username, $fRenovacion, $prestamoData){
		$sql = "INSERT INTO renovacion (Username, F_renovacion, Prestamo) 
		        VALUES ('$username', '$fRenovacion', '$prestamoData')";
		$result = RenovacionModel::Update_Data($sql);
  		return $result;
	}

	// ==================== ACTUALIZAR ====================
	public static function UpdateRenovacion($id, $username, $fRenovacion, $prestamoData){
		$sql = "UPDATE renovacion SET Username = '$username', F_renovacion = '$fRenovacion', Prestamo = '$prestamoData' 
		        WHERE ID = '$id'";
		$result = RenovacionModel::Update_Data($sql);
  		return $result;
	}

	// ==================== ELIMINAR ====================
	public static function DeleteRenovacion($id){
		$sql = "DELETE FROM renovacion WHERE ID = '$id'";
		$result = RenovacionModel::Update_Data($sql);
  		return $result;
	}
}
?>
