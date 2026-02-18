<?php

class EjemplarController
{
    private $model;

	function __construct()
	{
        require_once('../biblioteca-sistema/models/EjemplarModel.php');
		$this->model = new EjemplarModel();
	}

    // ==================== HELPER: DATALIST OPTIONS ====================
    private function inyectarOpcionesDatalist(&$formFields) {
        require_once('../biblioteca-sistema/models/ObraModel.php');
        require_once('../biblioteca-sistema/models/EstadoModel.php');
        require_once('../biblioteca-sistema/models/TSoporteModel.php');

        $obrasResult = ObraModel::ListarObra();
        $estadoResult = EstadoModel::ListarEstado();
        $soporteResult = TSoporteModel::ListarTSoporte();

        $opcionesObra = [];
        while($row = mysqli_fetch_array($obrasResult, MYSQLI_ASSOC)){
            // Use numeric indexes if worried about encoding of keys like 'Año'
            // But ObraModel::ListarObra explicitly has 'o.Año'
            $anio = $row['Año'] ?? '';
            $cutter = $row['Cutter_Autor'] ?? '';
            $cdd = $row['Codigo_CDD'] ?? '';
            
            $compositeId = "$anio|$cutter|$cdd";
            $displayText = ($row['Titulo'] ?? '') . ' - ' . ($row['Nombre_Autor'] ?? '') . " ($anio)";
            $opcionesObra[$compositeId] = $displayText;
        }

        $opcionesEstado = [];
        while($row = mysqli_fetch_assoc($estadoResult)){
            $opcionesEstado[$row['ID']] = $row['Descripcion'];
        }

        $opcionesSoporte = [];
        while($row = mysqli_fetch_assoc($soporteResult)){
            $opcionesSoporte[$row['ID']] = $row['Descripcion'];
        }

        foreach ($formFields as &$field) {
            if ($field['name'] === 'Obra_Display') {
                $field['options'] = $opcionesObra;
            } elseif ($field['name'] === 'Estado_Display') {
                $field['options'] = $opcionesEstado;
            } elseif ($field['name'] === 'Soporte_Display') {
                $field['options'] = $opcionesSoporte;
            }
        }
        unset($field);
    }

 	function ListarEjemplar(){
         $registros = $this->model->ListarEjemplar();
         $config = require('../biblioteca-sistema/config/forms/ejemplarform.php');
         extract($config['global']);
		 require_once('../biblioteca-sistema/views/generic/list.php');
	}

    // ==================== INSERTAR ====================

	function IngresarEjemplar($alert = null, $values = null){
         $config = require('../biblioteca-sistema/config/forms/ejemplarform.php');
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

         $this->inyectarOpcionesDatalist($formFields);
		 require_once('../biblioteca-sistema/views/generic/insert.php');
	}

    function IngresarEjemplar2(){
        $nEjemplar = trim($_POST['N_de_Ejemplar'] ?? '');
        $anio = trim($_POST['Año_Obras'] ?? '');
        $cutter = trim($_POST['Cutter_Autores'] ?? '');
        $cdd = trim($_POST['Codigo_CDD'] ?? '');
        $idEstado = trim($_POST['ID_Estado'] ?? '');
        $idSoporte = trim($_POST['ID_Soporte'] ?? '');

        // Validation
        if($nEjemplar === '' || $anio === '' || $cutter === '' || $cdd === '' || $idEstado === '' || $idSoporte === ''){
             $alert = ['type' => 'alert-warning', 'message' => 'Todos los campos son obligatorios. Por favor, asegúrese de seleccionar una Obra de la lista.'];
             $this->IngresarEjemplar($alert, $_POST);
             return;
        }

        // Verify Obra exists to avoid FK error
        require_once('../biblioteca-sistema/models/ObraModel.php');
        $checkObra = ObraModel::BuscarObraById($anio, $cutter, $cdd);
        if(mysqli_num_rows($checkObra) == 0){
             $alert = ['type' => 'alert-warning', 'message' => "La obra seleccionada (Año: $anio, Cutter: $cutter, CDD: $cdd) no existe."];
             $this->IngresarEjemplar($alert, $_POST);
             return;
        }

        // Check duplicates
        $existe = $this->model->BuscarEjemplarById($nEjemplar, $anio, $cutter, $cdd);
        if(mysqli_num_rows($existe) > 0){
             $alert = ['type' => 'alert-warning', 'message' => 'Este número de ejemplar ya está registrado para esta obra.'];
             $this->IngresarEjemplar($alert, $_POST);
             return;
        }

        $result = $this->model->IngresarEjemplar2($nEjemplar, $anio, $cutter, $cdd, $idEstado, $idSoporte);

        if ($result) {
             $alert = ['type' => 'alert-success', 'message' => 'Ejemplar registrado exitosamente.'];
        } else {
             $alert = ['type' => 'alert-warning', 'message' => 'Error al registrar en la base de datos.'];
        }

        $registros = $this->model->ListarEjemplar();
        $config = require('../biblioteca-sistema/config/forms/ejemplarform.php');
        extract($config['global']);
        extract($config['insert']);
        $this->inyectarOpcionesDatalist($formFields);
        require_once('../biblioteca-sistema/views/generic/insert.php');
    }

    // ==================== ACTUALIZAR ====================

    function UpdateEjemplar(){
        if(!isset($_GET['id'])) return;
        
        $nEjemplar = $_GET['id'];
        $anio = $_GET['anio'] ?? '';
        $cutter = $_GET['cutter'] ?? '';
        $cdd = $_GET['cdd'] ?? '';

        $res = $this->model->BuscarEjemplarById($nEjemplar, $anio, $cutter, $cdd);
        $data = mysqli_fetch_assoc($res);

        if(!$data){ echo "No encontrado"; return; }

        $config = require('../biblioteca-sistema/config/forms/ejemplarform.php');
        extract($config['global']);
        extract($config['update']);

        $this->inyectarOpcionesDatalist($formFields);

        foreach ($formFields as &$field) {
            if ($field['name'] == 'Año_Obras') {
                $field['value'] = $data['Año_Obras'];
            } elseif ($field['name'] == 'Obra_Display') {
                $field['value'] = $data['Titulo_Obra'] . " (" . $data['Año_Obras'] . ")";
            } elseif ($field['name'] == 'Estado_Display') {
                $field['value'] = $data['Desc_Estado'];
            } elseif ($field['name'] == 'Soporte_Display') {
                $field['value'] = $data['Desc_Soporte'];
            } elseif (isset($data[$field['name']])) {
                $field['value'] = $data[$field['name']];
            }
        }
        unset($field);

        require_once('../biblioteca-sistema/views/generic/update.php');
    }

    function UpdateEjemplar2(){
        $nEjemplar = $_POST['N_de_Ejemplar'];
        $anio = $_POST['Año_Obras'];
        $cutter = $_POST['Cutter_Autores'];
        $cdd = $_POST['Codigo_CDD'];
        $idEstado = $_POST['ID_Estado'];
        $idSoporte = $_POST['ID_Soporte'];

        $result = $this->model->UpdateEjemplar2($nEjemplar, $anio, $cutter, $cdd, $idEstado, $idSoporte);

        if ($result) {
             $alert = ['type' => 'alert-success', 'message' => 'Actualizado correctamente.'];
        } else {
             $alert = ['type' => 'alert-warning', 'message' => 'No se realizaron cambios o hubo un error.'];
        }

        $registros = $this->model->ListarEjemplar();
        $config = require('../biblioteca-sistema/config/forms/ejemplarform.php');
        extract($config['global']);
        extract($config['insert']);
        $this->inyectarOpcionesDatalist($formFields);
        require_once('../biblioteca-sistema/views/generic/insert.php');
    }

    // ==================== ELIMINAR ====================

    function DeleteEjemplar(){
        if(!isset($_GET['id'])) return;
        $nEjemplar = $_GET['id'];
        $anio = $_GET['anio'] ?? '';
        $cutter = $_GET['cutter'] ?? '';
        $cdd = $_GET['cdd'] ?? '';

        $this->model->DeleteEjemplar($nEjemplar, $anio, $cutter, $cdd);

        $registros = $this->model->ListarEjemplar();
        $config = require('../biblioteca-sistema/config/forms/ejemplarform.php');
        extract($config['global']);
        require_once('../biblioteca-sistema/views/generic/delete.php');
    }
}
?>
