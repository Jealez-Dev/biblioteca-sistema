<?php

class PrestamoModel
{
	function __construct()
	{
	}

	// FUNCIONES GENERICAS PARA CONSULTAR Y ACTUALIZAR
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
	public static function ListarPrestamo(){
		$sql = "SELECT p.*, 
				       o.Titulo AS Titulo_Obra,
				       CONCAT(u.Nombre, ' ', u.Apellido) AS Nombre_Prestatario,
				       p.Username_Prestamista AS Prestamista,
				       est.Descripcion AS Desc_Estado
				FROM prestamos p
				LEFT JOIN obras o ON (p.Año_Obras = o.Año AND p.Cutter_Autores = o.Cutter_Autor AND p.Codigo_CDD = o.Codigo_CDD)
				LEFT JOIN usuario_sin_acceso usa ON p.DNI_Prestatario = usa.DNI_Usuario
				LEFT JOIN usuario u ON usa.DNI_Usuario = u.DNI
				LEFT JOIN ejemplares ej ON (p.N_de_Ejemplar = ej.N_de_Ejemplar AND p.Año_Obras = ej.Año_Obras AND p.Cutter_Autores = ej.Cutter_Autores AND p.Codigo_CDD = ej.Codigo_CDD)
				LEFT JOIN estado est ON ej.ID_Estado = est.ID
				ORDER BY p.N_de_control DESC";
		$result = PrestamoModel::Get_Data($sql);
  		return $result;
	}

	// ==================== BUSCAR POR ID ====================
	public static function BuscarPrestamoById($nControl){
    	$sql = "SELECT p.*, 
    	               o.Titulo AS Titulo_Obra,
    	               CONCAT(u.Nombre, ' ', u.Apellido) AS Nombre_Prestatario,
    	               est.Descripcion AS Desc_Estado
    	        FROM prestamos p
    	        LEFT JOIN obras o ON (p.Año_Obras = o.Año AND p.Cutter_Autores = o.Cutter_Autor AND p.Codigo_CDD = o.Codigo_CDD)
    	        LEFT JOIN usuario_sin_acceso usa ON p.DNI_Prestatario = usa.DNI_Usuario
    	        LEFT JOIN usuario u ON usa.DNI_Usuario = u.DNI
    	        LEFT JOIN ejemplares ej ON (p.N_de_Ejemplar = ej.N_de_Ejemplar AND p.Año_Obras = ej.Año_Obras AND p.Cutter_Autores = ej.Cutter_Autores AND p.Codigo_CDD = ej.Codigo_CDD)
    	        LEFT JOIN estado est ON ej.ID_Estado = est.ID
    	        WHERE p.N_de_control = '$nControl'";
		$result = PrestamoModel::Get_Data($sql);
  		return $result;
	}

	// ==================== INSERTAR ====================
	public static function IngresarPrestamo($fPrestamo, $fDevolucion, $nEjemplar, $anio, $cutter, $cdd, $dniPrestatario, $usernamePrestamista, $tPrestamo){
		$sql = "INSERT INTO prestamos (F_prestamo, F_devolucion, N_de_Ejemplar, Año_Obras, Cutter_Autores, Codigo_CDD, DNI_Prestatario, Username_Prestamista, T_prestamo) 
		        VALUES ('$fPrestamo', '$fDevolucion', '$nEjemplar', '$anio', '$cutter', '$cdd', '$dniPrestatario', '$usernamePrestamista', '$tPrestamo')";
		$result = PrestamoModel::Update_Data($sql);
  		return $result;
	}

	// ==================== ACTUALIZAR FECHA DEVOLUCION (para Renovacion) ====================
	public static function UpdateFechaDevolucion($nControl, $newFDevolucion){
		$sql = "UPDATE prestamos SET F_devolucion = '$newFDevolucion' WHERE N_de_control = '$nControl'";
		$result = PrestamoModel::Update_Data($sql);
  		return $result;
	}

	// ==================== ELIMINAR ====================
	public static function DeletePrestamo($nControl){
		$sql = "DELETE FROM prestamos WHERE N_de_control = '$nControl'";
		$result = PrestamoModel::Update_Data($sql);
  		return $result;
	}

	// ==================== VALIDACIONES ====================

	// Verificar si el ejemplar está disponible (Estado ID=1)
	public static function CheckEjemplarDisponible($nEjemplar, $anio, $cutter, $cdd){
		$sql = "SELECT ID_Estado FROM ejemplares 
		        WHERE N_de_Ejemplar = '$nEjemplar' AND Año_Obras = '$anio' AND Cutter_Autores = '$cutter' AND Codigo_CDD = '$cdd'";
		$result = PrestamoModel::Get_Data($sql);
		if(mysqli_num_rows($result) > 0){
			$row = mysqli_fetch_assoc($result);
			return $row['ID_Estado'] == 1; // 1 = disponible
		}
		return false;
	}

	// Actualizar estado del ejemplar
	public static function UpdateEstadoEjemplar($nEjemplar, $anio, $cutter, $cdd, $nuevoEstado){
		$sql = "UPDATE ejemplares SET ID_Estado = '$nuevoEstado' 
		        WHERE N_de_Ejemplar = '$nEjemplar' AND Año_Obras = '$anio' AND Cutter_Autores = '$cutter' AND Codigo_CDD = '$cdd'";
		$result = PrestamoModel::Update_Data($sql);
  		return $result;
	}

	// Obtener limites de categoria del lector (n_de_dias, n_de_ejemplares)
	public static function GetLectorCategoryLimits($dniLector){
		$sql = "SELECT c.N_de_dias, c.N_de_ejemplares 
		        FROM usuario_sin_acceso usa
		        INNER JOIN catg_de_user_sa c ON usa.ID_Catg_de_User_SA = c.ID
		        WHERE usa.DNI_Usuario = '$dniLector'";
		$result = PrestamoModel::Get_Data($sql);
		if(mysqli_num_rows($result) > 0){
			return mysqli_fetch_assoc($result);
		}
		return null;
	}

	// Contar prestamos activos de un lector
	public static function CountPrestamosActivos($dniLector){
		$sql = "SELECT COUNT(*) as total FROM prestamos WHERE DNI_Prestatario = '$dniLector'";
		$result = PrestamoModel::Get_Data($sql);
		$row = mysqli_fetch_assoc($result);
		return (int)$row['total'];
	}
}
?>
