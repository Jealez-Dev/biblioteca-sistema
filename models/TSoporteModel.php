<?php 

class TSoporteModel
{
	// NOTE: Using native mysqli prepared statements for security as requested.
    // Ignoring the insecure generic Update_Data/Get_Data for new models.

	public static function ListarTSoporte(){
		include_once('core/Conectar.php');
		$conexion = Conectar::conexion();
		$sql = "SELECT * FROM t_soporte ORDER BY ID ASC";
        $result = mysqli_query($conexion, $sql);
        // We do not close connection here because the view might iterate over $result
        // But standard practice in this project seems to be to return the mysqli_result object.
  		return $result;
	}

    public static function BuscarTSoporteByQuery($query){
        include_once('core/Conectar.php');
		$conexion = Conectar::conexion();
        $sql = "SELECT * FROM t_soporte WHERE Descripcion LIKE ? OR ID LIKE ? ORDER BY ID ASC LIMIT 10";
        $stmt = mysqli_prepare($conexion, $sql);
        $likeQuery = "%$query%";
        mysqli_stmt_bind_param($stmt, "ss", $likeQuery, $likeQuery);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return $result;
    }
}
?>
