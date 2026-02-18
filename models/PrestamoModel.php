<?php

class PrestamoModel
{
    // ==================== LISTAR ====================
    public static function ListarPrestamo(){
        include_once('core/Conectar.php');
        $conexion = Conectar::conexion();
        // Prestamo table doesn't have obvious FKs in the schema provided.
        // Returning raw data.
        $sql = "SELECT * FROM prestamos ORDER BY N_de_control DESC";
        $result = mysqli_query($conexion, $sql);
        return $result;
    }

    // ==================== BUSCAR POR ID ====================
    public static function BuscarPrestamoById($nControl){
        include_once('core/Conectar.php');
        $conexion = Conectar::conexion();
        $sql = "SELECT * FROM prestamos WHERE N_de_control = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $nControl);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    // ==================== INSERTAR ====================
    public static function IngresarPrestamo($nControl, $fecha, $anio, $devolucion, $tipo){
        include_once('core/Conectar.php');
        $conexion = Conectar::conexion();
        
        $sql = "INSERT INTO prestamos (N_de_control, Fecha_de_prestamo, Año_del_prestamo, Devolucion_de_prestamo, Tipo_de_prestamo) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conexion, $sql);
        // Types: i, s (date string), i, s, s
        mysqli_stmt_bind_param($stmt, "isis", $nControl, $fecha, $anio, $devolucion, $tipo);
        
        // Error handling inside model? Or return bool.
        if(mysqli_stmt_execute($stmt)){
            return true;
        } else {
            return false;
        }
    }

    // ==================== ACTUALIZAR ====================
    public static function UpdatePrestamo($nControl, $fecha, $anio, $devolucion, $tipo){
        include_once('core/Conectar.php');
        $conexion = Conectar::conexion();
        
        $sql = "UPDATE prestamos 
                SET Fecha_de_prestamo = ?, Año_del_prestamo = ?, Devolucion_de_prestamo = ?, Tipo_de_prestamo = ?
                WHERE N_de_control = ?";
        
        $stmt = mysqli_prepare($conexion, $sql);
        // Types: s, i, s, s, i
        mysqli_stmt_bind_param($stmt, "sissi", $fecha, $anio, $devolucion, $tipo, $nControl);
        
        if(mysqli_stmt_execute($stmt)){
            return true;
        } else {
            return false;
        }
    }

    // ==================== ELIMINAR ====================
    public static function DeletePrestamo($nControl){
        include_once('core/Conectar.php');
        $conexion = Conectar::conexion();
        
        $sql = "DELETE FROM prestamos WHERE N_de_control = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $nControl);
        
        if(mysqli_stmt_execute($stmt)){
            return true;
        } else {
            return false;
        }
    }
}
?>
