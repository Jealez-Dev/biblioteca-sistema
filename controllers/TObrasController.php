<?php

class TObrasController
{
    private $tObrasModel;

	function __construct()
	{
        require_once('../biblioteca-sistema/models/TObrasModel.php');
		$this->tObrasModel = new TObrasModel();
	}

    // ==================== LISTAR (READ) ====================

 	function ListarTObras(){
         $registros = $this->tObrasModel->ListarTObras();
         $config = require('../biblioteca-sistema/config/forms/tobrasform.php');
         extract($config['global']);
		 require_once('../biblioteca-sistema/views/generic/list.php');
	}

	public function ListarTObras1(){
         $result_Listar= $this->tObrasModel->ListarTObras();
         return $result_Listar;
	}

    // ==================== INSERTAR (CREATE) ====================

    public function BuscarUltimoTObras(){
         $result_Listar = $this->tObrasModel->BuscarUltimoTObras();
         return $result_Listar;
	}

	function IngresarTObras($alert = null, $values = null){
		 $registros = $this->tObrasModel->ListarTObras();
         $config = require('../biblioteca-sistema/config/forms/tobrasform.php');
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

	public function IngresarTObras2(){
         // 1. Recoger datos del POST
         $ID = $_POST['ID'];
         $Descripcion = $_POST['Descripcion'];
         $Prestar = $_POST['Prestar'];

         // Validar que no exista
         [$dataExists, $alert, $values] = $this->validaTObras($ID);

         if($dataExists){
             $this->IngresarTObras($alert, $values);
             return;
         }

         // 2. Llamar al Modelo
         $result = $this->tObrasModel->IngresarTObras2($ID, $Descripcion, $Prestar);

         // 3. Preparar mensaje
         if ($result) {
             $alert = [
                 'type' => 'alert-success',
                 'message' => '<strong>¡Éxito!</strong> El tipo de obra ha sido registrado correctamente.'
             ];
         } else {
             $alert = [
                 'type' => 'alert-warning',
                 'message' => '<strong>Error:</strong> No se pudo registrar el tipo de obra. Verifique si ya existe.'
             ];
         }

         // 4. Volver a cargar la vista con la lista actualizada y el mensaje
         $registros = $this->tObrasModel->ListarTObras();
         $config = require('../biblioteca-sistema/config/forms/tobrasform.php');
         extract($config['global']);
         extract($config['insert']);
         
         require_once('../biblioteca-sistema/views/generic/insert.php');
	}

    // ==================== ACTUALIZAR (UPDATE) ====================

    public function BuscarTObrasById($ID){
         $result_Listar = $this->tObrasModel->BuscarTObrasById($ID);
         return $result_Listar;
	}

	function UpdateTObras($ID = null, $alert = null, $values = null){
         if(!isset($_GET['id'])) {
               echo "Error: ID no especificado";
               return;
         }
         $id = $_GET['id'];
         
         // 1. Obtener datos
         $resultado = $this->tObrasModel->BuscarTObrasById($id);
         $datosTObras = mysqli_fetch_assoc($resultado);

         // 2. Cargar configuración
         $registros = $this->tObrasModel->ListarTObras();
         $config = require('../biblioteca-sistema/config/forms/tobrasform.php');
         extract($config['global']);
         extract($config['update']);
         
         // 3. Inyectar valores en los campos del formulario
         foreach ($formFields as &$field) {
            if (isset($datosTObras[$field['name']])) {
                $field['value'] = $datosTObras[$field['name']];
            }
         }
         unset($field);

         require_once('../biblioteca-sistema/views/generic/update.php');
    }

	public function UpdateTObras2(){
         // 1. Recoger datos del POST
         $ID = $_POST['ID'];
         $Descripcion = $_POST['Descripcion'];
         $Prestar = $_POST['Prestar'];

         // 2. Llamar al Modelo
         $result = $this->tObrasModel->UpdateTObras2($ID, $Descripcion, $Prestar);

         // 3. Preparar mensaje
         if ($result) {
             $alert = [
                 'type' => 'alert-success',
                 'message' => '<strong>¡Éxito!</strong> El tipo de obra ha sido actualizado correctamente.'
             ];
         } else {
             $alert = [
                 'type' => 'alert-warning',
                 'message' => '<strong>Error:</strong> No se pudo actualizar el tipo de obra.'
             ];
         }

         // 4. Volver a cargar la vista
         $registros = $this->tObrasModel->ListarTObras();
         $config = require('../biblioteca-sistema/config/forms/tobrasform.php');
         extract($config['global']);
         extract($config['insert']); 
         
         require_once('../biblioteca-sistema/views/generic/insert.php');
	}

    // ==================== ELIMINAR (DELETE) ====================

	function DeleteTObras(){
         if(!isset($_GET['id'])) {
              echo "Error: ID no especificado"; return;
         }
         $id = $_GET['id'];
         
         // 1. Llamar al Modelo
         $result = $this->tObrasModel->DeleteTObras($id);

         // 2. Volver a cargar la lista
         $registros = $this->tObrasModel->ListarTObras();
         $config = require('../biblioteca-sistema/config/forms/tobrasform.php');
         extract($config['global']);         
         require_once('../biblioteca-sistema/views/generic/delete.php');
	}

    // ==================== BÚSQUEDA AJAX (JSON) ====================

    function BuscarTObrasJson(){
        // Endpoint para autocomplete AJAX
        header('Content-Type: application/json');
        $query = isset($_GET['q']) ? $_GET['q'] : '';
        
        if(strlen($query) < 1){
            echo json_encode([]);
            exit;
        }

        $resultados = $this->tObrasModel->BuscarTObrasByQuery($query);
        $data = [];
        while($row = mysqli_fetch_assoc($resultados)){
            $data[] = [
                'id' => $row['ID'],
                'text' => $row['ID'] . ' - ' . $row['Descripcion'],
                'descripcion' => $row['Descripcion'],
                'prestar' => $row['Prestar']
            ];
        }
        echo json_encode($data);
        exit;
    }

    // ==================== VALIDACIÓN ====================

    function validaTObras($ID){
        $valuesRepeat = $this->tObrasModel->ListarTObras();

         foreach ($valuesRepeat as $value) {
             if ($value['ID'] == $ID) {
                 $alert = [
                     'type' => 'alert-warning',
                     'message' => '<strong>Error:</strong> El tipo de obra con ese ID ya ha sido registrado anteriormente.'
                 ];
                 $values = [];
                 return [true, $alert, $values];
             }
         }
         return [false, null, null];
    }
}
?>
