<?php

class CddController
{
    private $cddModel;

	function __construct()
	{
        require_once('../biblioteca-sistema/models/CddModel.php');
		$this->cddModel = new CddModel();
	}

    // ==================== LISTAR (READ) ====================

 	function ListarCdd(){
         $registros = $this->cddModel->ListarCdd();
         $config = require('../biblioteca-sistema/config/forms/cddform.php');
         extract($config['global']);
		 require_once('../biblioteca-sistema/views/generic/list.php');
	}

	public function ListarCdd1(){
         $result_Listar= $this->cddModel->ListarCdd();
         return $result_Listar;
	}

    // ==================== INSERTAR (CREATE) ====================

    public function BuscarUltimoCdd(){
         $result_Listar = $this->cddModel->BuscarUltimoCdd();
         return $result_Listar;
	}

	function IngresarCdd($alert = null, $values = null){
		 $registros = $this->cddModel->ListarCdd();
         $config = require('../biblioteca-sistema/config/forms/cddform.php');
         extract($config['global']); // Para la lista
         extract($config['insert']); // Para el form
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

	public function IngresarCdd2(){
         // 1. Recoger datos del POST
         $Codigo = $_POST['Codigo'];
         $Descripcion = $_POST['Descripcion'];

         // Validar que no exista
         [$dataExists, $alert, $values] = $this->validaCdd($Codigo);

         if($dataExists){
             $this->IngresarCdd($alert, $values);
             return;
         }

         // 2. Llamar al Modelo
         $result = $this->cddModel->IngresarCdd2($Codigo, $Descripcion);

         // 3. Preparar mensaje
         if ($result) {
             $alert = [
                 'type' => 'alert-success',
                 'message' => '<strong>¡Éxito!</strong> La clasificación CDD ha sido registrada correctamente.'
             ];
         } else {
             $alert = [
                 'type' => 'alert-warning',
                 'message' => '<strong>Error:</strong> No se pudo registrar la clasificación CDD. Verifique si ya existe.'
             ];
         }

         // 4. Volver a cargar la vista con la lista actualizada y el mensaje
         $registros = $this->cddModel->ListarCdd();
         $config = require('../biblioteca-sistema/config/forms/cddform.php');
         extract($config['global']);
         extract($config['insert']);
         
         require_once('../biblioteca-sistema/views/generic/insert.php');
	}

    // ==================== ACTUALIZAR (UPDATE) ====================

    public function BuscarCddById($Codigo){
         $result_Listar = $this->cddModel->BuscarCddById($Codigo);
         return $result_Listar;
	}

	function UpdateCdd($Codigo = null, $alert = null, $values = null){
         if(!isset($_GET['id'])) {
               echo "Error: ID no especificado";
               return;
         }
         $id = $_GET['id'];
         
         // 1. Obtener datos
         $resultado = $this->cddModel->BuscarCddById($id);
         $datosCdd = mysqli_fetch_assoc($resultado);

         // 2. Cargar configuración
         $registros = $this->cddModel->ListarCdd();
         $config = require('../biblioteca-sistema/config/forms/cddform.php');
         extract($config['global']);
         extract($config['update']);
         
         // 3. Inyectar valores en los campos del formulario
         foreach ($formFields as &$field) {
            if (isset($datosCdd[$field['name']])) {
                $field['value'] = $datosCdd[$field['name']];
            }
         }
         unset($field);

         require_once('../biblioteca-sistema/views/generic/update.php');
    }

	public function UpdateCdd2(){
         // 1. Recoger datos del POST
         $Codigo = $_POST['Codigo'];
         $Descripcion = $_POST['Descripcion'];

         // 2. Llamar al Modelo
         $result = $this->cddModel->UpdateCdd2($Codigo, $Descripcion);

         // 3. Preparar mensaje
         if ($result) {
             $alert = [
                 'type' => 'alert-success',
                 'message' => '<strong>¡Éxito!</strong> La clasificación CDD ha sido actualizada correctamente.'
             ];
         } else {
             $alert = [
                 'type' => 'alert-warning',
                 'message' => '<strong>Error:</strong> No se pudo actualizar la clasificación CDD.'
             ];
         }

         // 4. Volver a cargar la vista
         $registros = $this->cddModel->ListarCdd();
         $config = require('../biblioteca-sistema/config/forms/cddform.php');
         extract($config['global']);
         extract($config['insert']); 
         
         require_once('../biblioteca-sistema/views/generic/insert.php');
	}

    // ==================== ELIMINAR (DELETE) ====================

	function DeleteCdd(){
         if(!isset($_GET['id'])) {
              echo "Error: ID no especificado"; return;
         }
         $id = $_GET['id'];
         
         // 1. Llamar al Modelo
         $result = $this->cddModel->DeleteCdd($id);

         // 2. Volver a cargar la lista
         $registros = $this->cddModel->ListarCdd();
         $config = require('../biblioteca-sistema/config/forms/cddform.php');
         extract($config['global']);         
         require_once('../biblioteca-sistema/views/generic/delete.php');
	}

    // ==================== BÚSQUEDA AJAX (JSON) ====================

    function BuscarCddJson(){
        // Endpoint para autocomplete AJAX
        // Devuelve JSON con resultados que coincidan con la búsqueda
        header('Content-Type: application/json');
        $query = isset($_GET['q']) ? $_GET['q'] : '';
        
        if(strlen($query) < 1){
            echo json_encode([]);
            exit;
        }

        $resultados = $this->cddModel->BuscarCddByQuery($query);
        $data = [];
        while($row = mysqli_fetch_assoc($resultados)){
            $data[] = [
                'id' => $row['Codigo'],
                'text' => $row['Codigo'] . ' - ' . $row['Descripcion'],
                'codigo' => $row['Codigo'],
                'descripcion' => $row['Descripcion']
            ];
        }
        echo json_encode($data);
        exit;
    }

    // ==================== VALIDACIÓN ====================

    function validaCdd($Codigo){
        $valuesRepeat = $this->cddModel->ListarCdd();

         foreach ($valuesRepeat as $value) {
             if ($value['Codigo'] == $Codigo) {
                 $alert = [
                     'type' => 'alert-warning',
                     'message' => '<strong>Error:</strong> El código CDD ya ha sido registrado anteriormente.'
                 ];
                 $values = [];
                 return [true, $alert, $values];
             }
         }
         return [false, null, null];
    }
}
?>
