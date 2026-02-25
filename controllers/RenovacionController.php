<?php

class RenovacionController
{
    private $model;
    private $prestamoModel;

	function __construct()
	{
        require_once('../biblioteca-sistema/models/RenovacionModel.php');
        require_once('../biblioteca-sistema/models/PrestamoModel.php');
		$this->model = new RenovacionModel();
		$this->prestamoModel = new PrestamoModel();
	}

    // ==================== HELPER: DATALIST OPTIONS ====================
    private function inyectarOpcionesDatalist(&$formFields) {
        // Cargar prestamos activos para el datalist
        $prestamos = PrestamoModel::ListarPrestamo();
        $opcionesPrestamo = [];
        while($row = mysqli_fetch_assoc($prestamos)){
            $titulo = $row['Titulo_Obra'] ?? 'Sin título';
            $prestatario = $row['Nombre_Prestatario'] ?? 'N/A';
            $displayText = 'Préstamo #' . $row['N_de_control'] . ' - ' . $titulo . ' - ' . $prestatario . ' (Dev: ' . $row['F_devolucion'] . ')';
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
 	function ListarRenovacion(){
         $registros = $this->model->ListarRenovacion();
         $config = require('../biblioteca-sistema/config/forms/renovacionform.php');
         extract($config['global']);
	     require_once('../biblioteca-sistema/views/generic/list.php');
	}

    // ==================== INSERT ====================
    
	function IngresarRenovacion($alert = null, $values = null){
         $registros = $this->model->ListarRenovacion();
         $config = require('../biblioteca-sistema/config/forms/renovacionform.php');
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
                        $field['value'] = 'Préstamo #' . $pRow['N_de_control'] . ' - ' . $titulo . ' - ' . $prestatario . ' (Dev: ' . $pRow['F_devolucion'] . ')';
                    }
                }
                unset($field);
            }
         }

         $this->inyectarOpcionesDatalist($formFields);
	     require_once('../biblioteca-sistema/views/generic/insert.php');
	}

    function IngresarRenovacion2(){
         // 1. Recoger datos
         $username = trim($_POST['Username'] ?? '');
         $fRenovacion = trim($_POST['F_renovacion'] ?? '');
         $nControlPrestamo = trim($_POST['N_control_prestamo'] ?? '');
         $newFDevolucion = trim($_POST['New_F_devolucion'] ?? '');

         // 2. Validar campos vacíos
         if(empty($username) || empty($fRenovacion) || empty($nControlPrestamo) || empty($newFDevolucion)){
             $alert = ['type' => 'alert-warning', 'message' => '<strong>Error:</strong> Todos los campos son obligatorios.'];
             $this->IngresarRenovacion($alert, $_POST);
             return;
         }

         // 3. Buscar el prestamo
         $prestamoResult = PrestamoModel::BuscarPrestamoById($nControlPrestamo);
         if(mysqli_num_rows($prestamoResult) == 0){
             $alert = ['type' => 'alert-warning', 'message' => '<strong>Error:</strong> El préstamo seleccionado no existe.'];
             $this->IngresarRenovacion($alert, $_POST);
             return;
         }
         $prestamo = mysqli_fetch_assoc($prestamoResult);

         // 4. Validar: new_f_devolucion >= current f_devolucion
         $currentDevolucion = new DateTime($prestamo['F_devolucion']);
         $newDevolucion = new DateTime($newFDevolucion);
         if($newDevolucion < $currentDevolucion){
             $alert = ['type' => 'alert-warning', 'message' => '<strong>Error:</strong> La nueva fecha de devolución no puede ser anterior a la fecha actual de devolución (' . $prestamo['F_devolucion'] . ').'];
             $this->IngresarRenovacion($alert, $_POST);
             return;
         }

         // 5. Construct Prestamo metadata string for historical log
         $prestamoDesc = 'Préstamo #' . $prestamo['N_de_control'] . ' - ' . $prestamo['Titulo_Obra'] . ' [Ej. #' . $prestamo['N_de_Ejemplar'] . '] - ' . $prestamo['Nombre_Prestatario'] . ' (' . $prestamo['DNI_Prestatario'] . ')';

         // 6. Update prestamo's f_devolucion
         PrestamoModel::UpdateFechaDevolucion($nControlPrestamo, $newFDevolucion);

         // 7. Insert renovation log
         $result = $this->model->IngresarRenovacion($username, $fRenovacion, $prestamoDesc);

         if ($result) {
              $alert = ['type' => 'alert-success', 'message' => '<strong>¡Éxito!</strong> Renovación registrada. La fecha de devolución del préstamo #' . $nControlPrestamo . ' ha sido actualizada.'];
         } else {
              $alert = ['type' => 'alert-warning', 'message' => '<strong>Error:</strong> No se pudo registrar la renovación.'];
         }

         $registros = $this->model->ListarRenovacion();
         $config = require('../biblioteca-sistema/config/forms/renovacionform.php');
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
    function UpdateRenovacion(){
        if(!isset($_GET['id'])) return;
        $id = $_GET['id'];
        
        $res = $this->model->BuscarRenovacionById($id);
        $data = mysqli_fetch_assoc($res);
        if(!$data){ echo "No encontrado"; return; }

        $config = require('../biblioteca-sistema/config/forms/renovacionform.php');
        extract($config['global']);
        extract($config['update']);

        $this->inyectarOpcionesDatalist($formFields);

        foreach ($formFields as &$field) {
            if ($field['name'] == 'Prestamo' && isset($data['Prestamo'])) {
                $field['value'] = $data['Prestamo'];
            } elseif (isset($data[$field['name']])) {
                $field['value'] = $data[$field['name']];
            }
        }
        unset($field);

        require_once('../biblioteca-sistema/views/generic/update.php');
    }

    function UpdateRenovacion2(){
         $id = $_POST['ID'];
         $username = trim($_POST['Username'] ?? '');
         $fRenovacion = trim($_POST['F_renovacion'] ?? '');
         $prestamoData = trim($_POST['Prestamo'] ?? '');

         // Update log only - NOT f_devolucion
         $result = $this->model->UpdateRenovacion($id, $username, $fRenovacion, $prestamoData);

         if ($result) {
              $alert = ['type' => 'alert-success', 'message' => '<strong>¡Éxito!</strong> Renovación actualizada.'];
         } else {
              $alert = ['type' => 'alert-warning', 'message' => '<strong>Error:</strong> No se pudo actualizar.'];
         }

         $registros = $this->model->ListarRenovacion();
         $config = require('../biblioteca-sistema/config/forms/renovacionform.php');
         extract($config['global']);
         require_once('../biblioteca-sistema/views/generic/list.php');
    }

    // ==================== DELETE ====================
    function DeleteRenovacion(){
        if(!isset($_GET['id'])) return;
        $id = $_GET['id'];
        
        // Delete log only - no prestamo side effects
        $result = $this->model->DeleteRenovacion($id);

        $registros = $this->model->ListarRenovacion();
        $config = require('../biblioteca-sistema/config/forms/renovacionform.php');
        extract($config['global']);
        require_once('../biblioteca-sistema/views/generic/delete.php');
    }
}
?>
