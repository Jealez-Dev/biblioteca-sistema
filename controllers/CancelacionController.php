<?php

class CancelacionController
{
    private $model;
    private $prestamoModel;

	function __construct()
	{
        require_once('../biblioteca-sistema/models/CancelacionModel.php');
        require_once('../biblioteca-sistema/models/PrestamoModel.php');
		$this->model = new CancelacionModel();
		$this->prestamoModel = new PrestamoModel();
	}

    // ==================== HELPER: DATALIST OPTIONS ====================
    private function inyectarOpcionesDatalist(&$formFields) {
        $prestamos = PrestamoModel::ListarPrestamo();
        $opcionesPrestamo = [];
        while($row = mysqli_fetch_assoc($prestamos)){
            $titulo = $row['Titulo_Obra'] ?? 'Sin título';
            $prestatario = $row['Nombre_Prestatario'] ?? 'N/A';
            $displayText = 'Préstamo #' . $row['N_de_control'] . ' - ' . $titulo . ' - ' . $prestatario;
            $opcionesPrestamo[$row['N_de_control']] = $displayText;
        }

        foreach ($formFields as &$field) {
            if ($field['name'] === 'Prestamo_Display') {
                $field['options'] = $opcionesPrestamo;
            }
        }
        unset($field);
    }

 	// ==================== LISTAR ====================
 	function ListarCancelacion(){
         $registros = $this->model->ListarCancelacion();
         $config = require('../biblioteca-sistema/config/forms/cancelacionform.php');
         extract($config['global']);
	     require_once('../biblioteca-sistema/views/generic/list.php');
	}

    // ==================== INSERT ====================
    
	function IngresarCancelacion($alert = null, $values = null){
         $registros = $this->model->ListarCancelacion();
         $config = require('../biblioteca-sistema/config/forms/cancelacionform.php');
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

         // Preset admin from session
         foreach ($formFields as &$field) {
            if ($field['name'] === 'Username') {
                $field['value'] = isset($_SESSION['User']) ? $_SESSION['User'] : '';
            }
         }
         unset($field);

         // If coming from Prestamo list with prestamo_id, pre-select
         if (isset($_GET['prestamo_id']) && $values === null) {
            $prestamoId = $_GET['prestamo_id'];
            $prestamoData = PrestamoModel::BuscarPrestamoById($prestamoId);
            $pRow = mysqli_fetch_assoc($prestamoData);
            if ($pRow) {
                foreach ($formFields as &$field) {
                    if ($field['name'] === 'N_control_prestamo') {
                        $field['value'] = $pRow['N_de_control'];
                    }
                    if ($field['name'] === 'Prestamo_Display') {
                        $titulo = $pRow['Titulo_Obra'] ?? 'Sin título';
                        $prestatario = $pRow['Nombre_Prestatario'] ?? 'N/A';
                        $field['value'] = 'Préstamo #' . $pRow['N_de_control'] . ' - ' . $titulo . ' - ' . $prestatario;
                    }
                }
                unset($field);
            }
         }

         $this->inyectarOpcionesDatalist($formFields);
	     require_once('../biblioteca-sistema/views/generic/insert.php');
	}

    function IngresarCancelacion2(){
         // 1. Recoger datos
         $username = trim($_POST['Username'] ?? '');
         $fCancelacion = trim($_POST['F_cancelacion'] ?? '');
         $nControlPrestamo = trim($_POST['N_control_prestamo'] ?? '');

         // 2. Validar campos vacíos
         if(empty($username) || empty($fCancelacion) || empty($nControlPrestamo)){
             $alert = ['type' => 'alert-warning', 'message' => '<strong>Error:</strong> Todos los campos son obligatorios.'];
             $this->IngresarCancelacion($alert, $_POST);
             return;
         }

         // 3. Buscar el prestamo para obtener datos del ejemplar
         $prestamoResult = PrestamoModel::BuscarPrestamoById($nControlPrestamo);
         if(mysqli_num_rows($prestamoResult) == 0){
             $alert = ['type' => 'alert-warning', 'message' => '<strong>Error:</strong> El préstamo seleccionado no existe.'];
             $this->IngresarCancelacion($alert, $_POST);
             return;
         }
         $prestamo = mysqli_fetch_assoc($prestamoResult);

         // 4. Construct Prestamo metadata string for historical log
         $prestamoDesc = 'Préstamo #' . $prestamo['N_de_control'] . ' - ' . $prestamo['Titulo_Obra'] . ' [Ej. #' . $prestamo['N_de_Ejemplar'] . '] - ' . $prestamo['Nombre_Prestatario'] . ' (' . $prestamo['DNI_Prestatario'] . ')';

         // 5. Revert Ejemplar estado to "disponible" (ID=1)
         PrestamoModel::UpdateEstadoEjemplar(
             $prestamo['N_de_Ejemplar'], 
             $prestamo['Año_Obras'], 
             $prestamo['Cutter_Autores'], 
             $prestamo['Codigo_CDD'], 
             1 // disponible
         );

         // 6. Delete the Prestamo
         PrestamoModel::DeletePrestamo($nControlPrestamo);

         // 7. Insert cancellation log (using metadata string)
         $result = $this->model->IngresarCancelacion($username, $fCancelacion, $prestamoDesc);

         if ($result) {
              $alert = ['type' => 'alert-success', 'message' => '<strong>¡Éxito!</strong> Préstamo #' . $nControlPrestamo . ' cancelado. El ejemplar ha sido devuelto a estado disponible.'];
         } else {
              $alert = ['type' => 'alert-warning', 'message' => '<strong>Error:</strong> No se pudo registrar la cancelación.'];
         }

         $registros = $this->model->ListarCancelacion();
         $config = require('../biblioteca-sistema/config/forms/cancelacionform.php');
         extract($config['global']);
         extract($config['insert']);
         $this->inyectarOpcionesDatalist($formFields);
         
         // Preset admin
         foreach ($formFields as &$field) {
            if ($field['name'] === 'Username') {
                $field['value'] = isset($_SESSION['User']) ? $_SESSION['User'] : '';
            }
         }
         unset($field);
         
         require_once('../biblioteca-sistema/views/generic/insert.php');
    }

    // ==================== UPDATE ====================
    function UpdateCancelacion(){
        if(!isset($_GET['id'])) return;
        $id = $_GET['id'];
        
        $res = $this->model->BuscarCancelacionById($id);
        $data = mysqli_fetch_assoc($res);
        if(!$data){ echo "No encontrado"; return; }

        $config = require('../biblioteca-sistema/config/forms/cancelacionform.php');
        extract($config['global']);
        extract($config['update']);

        foreach ($formFields as &$field) {
            if (isset($data[$field['name']])) {
                $field['value'] = $data[$field['name']];
            }
        }
        unset($field);

        require_once('../biblioteca-sistema/views/generic/update.php');
    }

    function UpdateCancelacion2(){
         $id = $_POST['ID'];
         $username = trim($_POST['Username'] ?? '');
         $fCancelacion = trim($_POST['F_cancelacion'] ?? '');
         $prestamoData = trim($_POST['Prestamo'] ?? '');

         // Update log only - no prestamo side effects
         $result = $this->model->UpdateCancelacion($id, $username, $fCancelacion, $prestamoData);

         if ($result) {
              $alert = ['type' => 'alert-success', 'message' => '<strong>¡Éxito!</strong> Cancelación actualizada.'];
         } else {
              $alert = ['type' => 'alert-warning', 'message' => '<strong>Error:</strong> No se pudo actualizar.'];
         }

         $registros = $this->model->ListarCancelacion();
         $config = require('../biblioteca-sistema/config/forms/cancelacionform.php');
         extract($config['global']);
         require_once('../biblioteca-sistema/views/generic/list.php');
    }

    // ==================== DELETE ====================
    function DeleteCancelacion(){
        if(!isset($_GET['id'])) return;
        $id = $_GET['id'];
        
        // Delete log only - no prestamo side effects
        $result = $this->model->DeleteCancelacion($id);

        $registros = $this->model->ListarCancelacion();
        $config = require('../biblioteca-sistema/config/forms/cancelacionform.php');
        extract($config['global']);
        require_once('../biblioteca-sistema/views/generic/delete.php');
    }
}
?>
