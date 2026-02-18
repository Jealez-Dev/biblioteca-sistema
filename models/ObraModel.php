<?php

class ObraModel
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

	public static function ListarObra(){
		$sql = "SELECT o.Año, o.Cutter_Autor, o.Codigo_CDD, o.Titulo, o.Edicion, 
				       o.Otros_Autores, o.ISBN, o.ISNN, o.Descripcion, o.L_Publicacion, 
				       o.N_Dep_Legal, o.ID_Editorial, o.ID_T_Obras,
				       a.Nombre AS Nombre_Autor,
				       e.Nombre AS Nombre_Editorial,
				       c.Descripcion AS Desc_CDD,
				       t.Descripcion AS Desc_TObras
				FROM obras o
				LEFT JOIN autores a ON o.Cutter_Autor = a.Cutter
				LEFT JOIN editorial e ON o.ID_Editorial = e.ID
				LEFT JOIN cdd c ON o.Codigo_CDD = c.Codigo
				LEFT JOIN t_obras t ON o.ID_T_Obras = t.ID
				ORDER BY o.Titulo asc";
		$result = ObraModel::Get_Data($sql);
  		return $result;
	}

    // ==================== BUSCAR POR ID COMPUESTO ====================

	public static function BuscarObraById($anio, $cutter, $codigoCdd){
    	$sql = "SELECT o.*, 
    	               a.Nombre AS Nombre_Autor,
    	               e.Nombre AS Nombre_Editorial,
    	               c.Descripcion AS Desc_CDD,
    	               t.Descripcion AS Desc_TObras
    	        FROM obras o
    	        LEFT JOIN autores a ON o.Cutter_Autor = a.Cutter
    	        LEFT JOIN editorial e ON o.ID_Editorial = e.ID
    	        LEFT JOIN cdd c ON o.Codigo_CDD = c.Codigo
    	        LEFT JOIN t_obras t ON o.ID_T_Obras = t.ID
    	        WHERE o.Año = '$anio' AND o.Cutter_Autor = '$cutter' AND o.Codigo_CDD = '$codigoCdd'";
		$result = ObraModel::Get_Data($sql);
  		return $result;
	}

    // ==================== INSERTAR ====================

	public static function IngresarObra2($anio, $cutterAutor, $codigoCdd, $titulo, $edicion, 
	                                      $otrosAutores, $isbn, $isnn, $descripcion, 
	                                      $lPublicacion, $nDepLegal, $idEditorial, $idTObras){
		$sql = "INSERT INTO obras (Año, Cutter_Autor, Codigo_CDD, Titulo, Edicion, 
		        Otros_Autores, ISBN, ISNN, Descripcion, L_Publicacion, N_Dep_Legal, 
		        ID_Editorial, ID_T_Obras) 
		        VALUES ('$anio', '$cutterAutor', '$codigoCdd', '$titulo', '$edicion', 
		        '$otrosAutores', '$isbn', '$isnn', '$descripcion', '$lPublicacion', 
		        '$nDepLegal', '$idEditorial', '$idTObras')";
		$result = ObraModel::Update_Data($sql);
  		return $result;
	}

    // ==================== ACTUALIZAR ====================

	public static function UpdateObra2($anio, $cutterAutor, $codigoCdd, $titulo, $edicion,
	                                    $otrosAutores, $isbn, $isnn, $descripcion,
	                                    $lPublicacion, $nDepLegal, $idEditorial, $idTObras){

		$sql = "UPDATE obras SET 
		        Titulo = '$titulo', 
		        Edicion = '$edicion', 
		        Otros_Autores = '$otrosAutores', 
		        ISBN = '$isbn', 
		        ISNN = '$isnn', 
		        Descripcion = '$descripcion', 
		        L_Publicacion = '$lPublicacion', 
		        N_Dep_Legal = '$nDepLegal', 
		        ID_Editorial = '$idEditorial', 
		        ID_T_Obras = '$idTObras' 
		        WHERE Año = '$anio' AND Cutter_Autor = '$cutterAutor' AND Codigo_CDD = '$codigoCdd'";
		$result = ObraModel::Update_Data($sql);
  		return $result;
	}

    // ==================== ELIMINAR ====================

	public static function DeleteObra($anio, $cutter, $codigoCdd){
		$sql = "DELETE FROM obras WHERE Año = '$anio' AND Cutter_Autor = '$cutter' AND Codigo_CDD = '$codigoCdd'";
		$result = ObraModel::Update_Data($sql);
  		return $result;
	}
}
?>
