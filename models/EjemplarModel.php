<?php

class EjemplarModel
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

	
    // ==================== LISTAR (con JOINs para mostrar nombres) ====================

	public static function ListarEjemplar(){
		$sql = "SELECT e.*, 
				       o.Titulo AS Titulo_Obra,
				       est.Descripcion AS Desc_Estado,
				       sop.Descripcion AS Desc_Soporte
				FROM ejemplares e
				LEFT JOIN obras o ON (e.Año_Obras = o.Año AND e.Cutter_Autores = o.Cutter_Autor AND e.Codigo_CDD = o.Codigo_CDD)
				LEFT JOIN estado est ON e.ID_Estado = est.ID
				LEFT JOIN t_soporte sop ON e.ID_Soporte = sop.ID
				ORDER BY e.N_de_Ejemplar ASC"; 
		$result = EjemplarModel::Get_Data($sql);
  		return $result;
	}

    // ==================== BUSCAR POR ID COMPUESTO ====================

	public static function BuscarEjemplarById($nEjemplar, $anio, $cutter, $codigoCdd){
    	$sql = "SELECT e.*, 
    	               o.Titulo AS Titulo_Obra,
    	               est.Descripcion AS Desc_Estado,
    	               sop.Descripcion AS Desc_Soporte
    	        FROM ejemplares e
    	        LEFT JOIN obras o ON (e.Año_Obras = o.Año AND e.Cutter_Autores = o.Cutter_Autor AND e.Codigo_CDD = o.Codigo_CDD)
    	        LEFT JOIN estado est ON e.ID_Estado = est.ID
    	        LEFT JOIN t_soporte sop ON e.ID_Soporte = sop.ID
    	        WHERE e.N_de_Ejemplar = '$nEjemplar' AND e.Año_Obras = '$anio' AND e.Cutter_Autores = '$cutter' AND e.Codigo_CDD = '$codigoCdd'";
		$result = EjemplarModel::Get_Data($sql);
  		return $result;
	}

    // ==================== INSERTAR ====================

	public static function IngresarEjemplar2($nEjemplar, $anio, $cutter, $cdd, $idEstado, $idSoporte){
		$sql = "INSERT INTO ejemplares (N_de_Ejemplar, Año_Obras, Cutter_Autores, Codigo_CDD, ID_Estado, ID_Soporte) 
		        VALUES ('$nEjemplar', '$anio', '$cutter', '$cdd', '$idEstado', '$idSoporte')";
		$result = EjemplarModel::Update_Data($sql);
  		return $result;
	}

    // ==================== ACTUALIZAR ====================

	public static function UpdateEjemplar2($nEjemplar, $anio, $cutter, $cdd, $idEstado, $idSoporte){
		$sql = "UPDATE ejemplares SET 
		        ID_Estado = '$idEstado', 
		        ID_Soporte = '$idSoporte' 
		        WHERE N_de_Ejemplar = '$nEjemplar' AND Año_Obras = '$anio' AND Cutter_Autores = '$cutter' AND Codigo_CDD = '$cdd'";
		$result = EjemplarModel::Update_Data($sql);
  		return $result;
	}

    // ==================== ELIMINAR ====================

	public static function DeleteEjemplar($nEjemplar, $anio, $cutter, $codigoCdd){
		$sql = "DELETE FROM ejemplares WHERE N_de_Ejemplar = '$nEjemplar' AND Año_Obras = '$anio' AND Cutter_Autores = '$cutter' AND Codigo_CDD = '$codigoCdd'";
		$result = EjemplarModel::Update_Data($sql);
  		return $result;
	}
}
?>
