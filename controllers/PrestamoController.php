<?php

class PrestamoController
{
    private $model;

	function __construct()
	{
        require_once('../biblioteca-sistema/models/PrestamoModel.php');
		$this->model = new PrestamoModel();
	}

    // ==================== HELPER: DATALIST OPTIONS ====================
    private function inyectarOpcionesDatalist(&$formFields) {
        require_once('../biblioteca-sistema/models/EjemplarModel.php');
        require_once('../biblioteca-sistema/models/LectorModel.php');
        require_once('../biblioteca-sistema/models/UserModel.php');

        // Ejemplares disponibles
        $ejemplares = EjemplarModel::ListarEjemplar();
        $opcionesEjemplar = [];
        while($row = mysqli_fetch_assoc($ejemplares)){
            $compositeId = $row['N_de_Ejemplar'] . '|' . $row['Año_Obras'] . '|' . $row['Cutter_Autores'] . '|' . $row['Codigo_CDD'];
            $titulo = $row['Titulo_Obra'] ?? 'Sin título';
            $estado = $row['Desc_Estado'] ?? '';
            $displayText = 'Ej.' . $row['N_de_Ejemplar'] . ' - ' . $titulo . ' (' . $row['Año_Obras'] . ') [' . $estado . ']';
            $opcionesEjemplar[$compositeId] = $displayText;
        }

        // Lectores (usuario_sin_acceso + usuario)
        $lectores = LectorModel::ListarLector();
        $opcionesLector = [];
        // We need user names, so let's load them
        $usuarios = UserModel::ListarUser();
        $userMap = [];
        while($u = mysqli_fetch_assoc($usuarios)){
            $userMap[$u['DNI']] = $u['Nombre'] . ' ' . $u['Apellido'];
        }
        while($row = mysqli_fetch_assoc($lectores)){
            $nombre = isset($userMap[$row['DNI_Usuario']]) ? $userMap[$row['DNI_Usuario']] : 'DNI: ' . $row['DNI_Usuario'];
            $opcionesLector[$row['DNI_Usuario']] = $nombre . ' - DNI: ' . $row['DNI_Usuario'];
        }

        // Inyectar
        foreach ($formFields as &$field) {
            if ($field['name'] === 'Ejemplar_Display') {
                $field['options'] = $opcionesEjemplar;
            } elseif ($field['name'] === 'Prestatario_Display') {
                $field['options'] = $opcionesLector;
            }
        }
        unset($field);
    }

 	// ==================== LISTAR ====================
 	function ListarPrestamo(){
         $registros = $this->model->ListarPrestamo();
         $config = require('../biblioteca-sistema/config/forms/prestamoform.php');
         extract($config['global']);
	     require_once('../biblioteca-sistema/views/generic/list.php');
	}

	public function ListarPrestamo1(){
         $result_Listar = $this->model->ListarPrestamo();
         return $result_Listar;
	}

    // ==================== INSERT ====================
    
	function IngresarPrestamo($alert = null, $values = null){
         $registros = $this->model->ListarPrestamo();
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

         // Preset prestamista from session
         foreach ($formFields as &$field) {
            if ($field['name'] === 'Username_Prestamista') {
                $field['value'] = isset($_SESSION['User']) ? $_SESSION['User'] : '';
            }
         }
         unset($field);

         $this->inyectarOpcionesDatalist($formFields);
	     require_once('../biblioteca-sistema/views/generic/insert.php');
	}

    function IngresarPrestamo2(){
         // 1. Recoger datos
         $fPrestamo = trim($_POST['F_prestamo'] ?? '');
         $fDevolucion = trim($_POST['F_devolucion'] ?? '');
         $nEjemplar = trim($_POST['N_de_Ejemplar'] ?? '');
         $anio = trim($_POST['Año_Obras'] ?? '');
         $cutter = trim($_POST['Cutter_Autores'] ?? '');
         $cdd = trim($_POST['Codigo_CDD'] ?? '');
         $dniPrestatario = trim($_POST['DNI_Prestatario'] ?? '');
         $usernamePrestamista = trim($_POST['Username_Prestamista'] ?? '');
         $tPrestamo = trim($_POST['T_prestamo'] ?? '');

         // 2. Validar campos vacíos
         if(empty($fPrestamo) || empty($fDevolucion) || empty($nEjemplar) || empty($anio) || 
            empty($cutter) || empty($cdd) || empty($dniPrestatario) || empty($usernamePrestamista) || empty($tPrestamo)){
             $alert = ['type' => 'alert-warning', 'message' => '<strong>Error:</strong> Todos los campos son obligatorios.'];
             $this->IngresarPrestamo($alert, $_POST);
             return;
         }

         // 3. Validar: f_devolucion >= f_prestamo
         $datePrestamo = new DateTime($fPrestamo);
         $dateDevolucion = new DateTime($fDevolucion);
         if($dateDevolucion < $datePrestamo){
             $alert = ['type' => 'alert-warning', 'message' => '<strong>Error:</strong> La fecha de devolución no puede ser anterior a la fecha de préstamo.'];
             $this->IngresarPrestamo($alert, $_POST);
             return;
         }

         // 4. Validar: si "En sala", ambas fechas deben ser iguales
         if($tPrestamo === 'En sala' && $fPrestamo !== $fDevolucion){
             $alert = ['type' => 'alert-warning', 'message' => '<strong>Error:</strong> Para préstamos "En sala", la fecha de préstamo y devolución deben ser la misma.'];
             $this->IngresarPrestamo($alert, $_POST);
             return;
         }

         // 5. Validar: diferencia de días no exceda n_de_dias del lector
         $limits = $this->model->GetLectorCategoryLimits($dniPrestatario);
         if(!$limits){
             $alert = ['type' => 'alert-warning', 'message' => '<strong>Error:</strong> No se encontró la categoría del lector.'];
             $this->IngresarPrestamo($alert, $_POST);
             return;
         }

         $diffDays = $datePrestamo->diff($dateDevolucion)->days;
         if($diffDays > $limits['N_de_dias']){
             $alert = ['type' => 'alert-warning', 'message' => '<strong>Error:</strong> La diferencia de días (' . $diffDays . ') excede el máximo permitido (' . $limits['N_de_dias'] . ' días) para la categoría del lector.'];
             $this->IngresarPrestamo($alert, $_POST);
             return;
         }

         // 6. Validar: número de prestamos activos no exceda n_de_ejemplares
         $activePrestamos = $this->model->CountPrestamosActivos($dniPrestatario);
         if($activePrestamos >= $limits['N_de_ejemplares']){
             $alert = ['type' => 'alert-warning', 'message' => '<strong>Error:</strong> El lector ya tiene ' . $activePrestamos . ' préstamos activos. Máximo permitido: ' . $limits['N_de_ejemplares'] . '.'];
             $this->IngresarPrestamo($alert, $_POST);
             return;
         }

         // 7. Validar: ejemplar disponible (Estado = 1)
         if(!$this->model->CheckEjemplarDisponible($nEjemplar, $anio, $cutter, $cdd)){
             $alert = ['type' => 'alert-warning', 'message' => '<strong>Error:</strong> El ejemplar seleccionado no está disponible para préstamo.'];
             $this->IngresarPrestamo($alert, $_POST);
             return;
         }

         // 8. TODO ALL CHECKS PASSED: Update estado + Insert prestamo
         // Actualizar estado del ejemplar a "prestado" (ID=2)
         $this->model->UpdateEstadoEjemplar($nEjemplar, $anio, $cutter, $cdd, 2);

         // Insertar prestamo
         $result = $this->model->IngresarPrestamo($fPrestamo, $fDevolucion, $nEjemplar, $anio, $cutter, $cdd, $dniPrestatario, $usernamePrestamista, $tPrestamo);

         if ($result) {
              $alert = ['type' => 'alert-success', 'message' => '<strong>¡Éxito!</strong> Préstamo registrado correctamente.'];
         } else {
              // Revert estado if insert failed
              $this->model->UpdateEstadoEjemplar($nEjemplar, $anio, $cutter, $cdd, 1);
              $alert = ['type' => 'alert-warning', 'message' => '<strong>Error:</strong> No se pudo registrar el préstamo.'];
         }

         $registros = $this->model->ListarPrestamo();
         $config = require('../biblioteca-sistema/config/forms/prestamoform.php');
         extract($config['global']);
         extract($config['insert']);
         $this->inyectarOpcionesDatalist($formFields);
         
         // Preset prestamista
         foreach ($formFields as &$field) {
            if ($field['name'] === 'Username_Prestamista') {
                $field['value'] = isset($_SESSION['User']) ? $_SESSION['User'] : '';
            }
         }
         unset($field);
         
         require_once('../biblioteca-sistema/views/generic/insert.php');
    }

    // ==================== UPDATE (redirect to Renovacion) ====================
    function UpdatePrestamo(){
        if(!isset($_GET['id'])) return;
        $id = $_GET['id'];
        header("Location: ?controller=Renovacion&action=IngresarRenovacion&prestamo_id=" . $id);
        exit;
    }

    // ==================== DELETE (redirect to Cancelacion) ====================
    function DeletePrestamo(){
        if(!isset($_GET['id'])) return;
        $id = $_GET['id'];
        header("Location: ?controller=Cancelacion&action=IngresarCancelacion&prestamo_id=" . $id);
        exit;
    }
}
?>
