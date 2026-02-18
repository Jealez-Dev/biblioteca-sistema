<?php 

class EstadoModel
{
	public static function ListarEstado(){
		include_once('core/Conectar.php');
		$conexion = Conectar::conexion();
		$sql = "SELECT * FROM estado ORDER BY ID ASC";
        $result = mysqli_query($conexion, $sql);
  		return $result;
	}

    public static function BuscarEstadoByQuery($query){
        include_once('core/Conectar.php');
		$conexion = Conectar::conexion();
        $sql = "SELECT * FROM estado WHERE Descripcion LIKE ? OR ID LIKE ? ORDER BY ID ASC LIMIT 10";
        $stmt = mysqli_prepare($conexion, $sql);
        $likeQuery = "%$query%";
        mysqli_stmt_bind_param($stmt, "ss", $likeQuery, $likeQuery);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return $result;
    }
}
?>
