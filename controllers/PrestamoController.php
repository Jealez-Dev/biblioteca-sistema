<?php

class PrestamoController
{
    private $model;

	function __construct()
	{
        require_once('../biblioteca-sistema/models/PrestamoModel.php');
		$this->model = new PrestamoModel();
	}

 	function ListarPrestamo(){
         $registros = $this->model->ListarPrestamo();
         $config = require('../biblioteca-sistema/config/forms/prestamoform.php');
         extract($config['global']);
		 require_once('../biblioteca-sistema/views/generic/list.php');
	}
    public function ListarPrestamo1(){
         $result_Listar= $this->model->ListarPrestamo();
         return $result_Listar;
	}

    // ==================== INSERT ====================
    
	function IngresarPrestamo($alert = null, $values = null){
         $config = require('../biblioteca-sistema/config/forms/prestamoform.php');
         extract($config['global']);
         extract($config['insert']);

         if ($values) {
            foreach ($formFields as &$field) {
                if (isset($values[$field['name']])) {
                    $field['value'] = $values[$field['name']];
                }
            }
            unset($field);
         }
		 require_once('../biblioteca-sistema/views/generic/insert.php');
	}

    function IngresarPrestamo2(){ // Process
         // Recoger datos
         $nControl = $_POST['N_de_control'] ?? '';
         $fecha = $_POST['Fecha_de_prestamo'] ?? '';
         $anio = $_POST['Año_del_prestamo'] ?? '';
         $devolucion = $_POST['Devolucion_de_prestamo'] ?? '';
         $tipo = $_POST['Tipo_de_prestamo'] ?? '';

         // Validar
         if(empty($nControl) || empty($fecha) || empty($anio) || empty($devolucion) || empty($tipo)){
             $alert = ['type' => 'alert-warning', 'message' => 'Todos los campos son obligatorios.'];
             $this->IngresarPrestamo($alert, $_POST);
             return;
         }

         // Duplicados
         $existe = $this->model->BuscarPrestamoById($nControl);
         if(mysqli_num_rows($existe) > 0){
             $alert = ['type' => 'alert-warning', 'message' => 'El N° de control ya existe.'];
             $this->IngresarPrestamo($alert, $_POST);
             return;
         }

         $result = $this->model->IngresarPrestamo($nControl, $fecha, $anio, $devolucion, $tipo);

         if ($result) {
             $alert = ['type' => 'alert-success', 'message' => 'Prestamo registrado exitosamente.'];
         } else {
             $alert = ['type' => 'alert-warning', 'message' => 'Error al registrar.'];
         }

         $registros = $this->model->ListarPrestamo();
         $config = require('../biblioteca-sistema/config/forms/prestamoform.php');
         extract($config['global']);
         extract($config['insert']);
         require_once('../biblioteca-sistema/views/generic/insert.php');
    }

    // ==================== UPDATE ====================

    function UpdatePrestamo(){
        if(!isset($_GET['id'])) return;
        $id = $_GET['id'];
        
        $res = $this->model->BuscarPrestamoById($id);
        $data = mysqli_fetch_assoc($res);
        if(!$data) return;

        $config = require('../biblioteca-sistema/config/forms/prestamoform.php');
        extract($config['global']);
        extract($config['update']);

        foreach ($formFields as &$field) {
            if(isset($data[$field['name']])){
                 $field['value'] = $data[$field['name']];
            }
        }
        unset($field);
        require_once('../biblioteca-sistema/views/generic/update.php');
    }

    function UpdatePrestamo2(){
         $nControl = $_POST['N_de_control'];
         $fecha = $_POST['Fecha_de_prestamo'];
         $anio = $_POST['Año_del_prestamo'];
         $devolucion = $_POST['Devolucion_de_prestamo'];
         $tipo = $_POST['Tipo_de_prestamo'];

         $result = $this->model->UpdatePrestamo($nControl, $fecha, $anio, $devolucion, $tipo);

         if ($result) {
             $alert = ['type' => 'alert-success', 'message' => 'Actualizado correctamente.'];
         } else {
             $alert = ['type' => 'alert-warning', 'message' => 'Error al actualizar.'];
         }

         $registros = $this->model->ListarPrestamo();
         $config = require('../biblioteca-sistema/config/forms/prestamoform.php');
         extract($config['global']);
         extract($config['insert']);
         require_once('../biblioteca-sistema/views/generic/insert.php');
    }

    // ==================== DELETE ====================

    function DeletePrestamo(){
        if(!isset($_GET['id'])) return;
        $id = $_GET['id'];
        
        $this->model->DeletePrestamo($id);

        $registros = $this->model->ListarPrestamo();
        $config = require('../biblioteca-sistema/config/forms/prestamoform.php');
        extract($config['global']);
        require_once('../biblioteca-sistema/views/generic/delete.php');
    }
}
?>
