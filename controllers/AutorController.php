<?php

class AutorController
{

    private $autorModel;

	function __construct()
	{
        require_once('../biblioteca-sistema/models/AutorModel.php');
		$this->autorModel = new AutorModel();
	}

 	function ListarAutor(){
         $registros = $this->autorModel->ListarAutor();
         $config = require('../biblioteca-sistema/config/forms/autorform.php');
         extract($config['global']);
		 require_once('../biblioteca-sistema/views/generic/list.php');
	}

	public function ListarAutor1(){
         $result_Listar= $this->autorModel->ListarAutor();
         return $result_Listar;
	}
  
  // Para insertar

    public function BuscarUltimoAutor(){
         $result_Listar = $this->autorModel->BuscarUltimoAutor();
         return $result_Listar;
	}

	function IngresarAutor($alert = null){
		 $registros = $this->autorModel->ListarAutor();
         $config = require('../biblioteca-sistema/config/forms/autorform.php');
         extract($config['global']); // Para la lista
         extract($config['insert']); // Para el form

		 require_once('../biblioteca-sistema/views/generic/insert.php');
	}

	public function IngresarAutor2(){
         // 1. Recoger datos del POST
         // Nota: Idealmente validaríamos que existan
         $Cutter = $_POST['Cutter'];
         $Nombre = $_POST['Nombre'];
         $Nacionalidad = $_POST['Nacionalidad'];

        $alert = null;
         if($this->validarAutor($Cutter)){
            $alert = [
                'type' => 'alert-warning',
                'message' => '<strong>Error:</strong> El Cutter: '.$Cutter.' ya existe. Reingrese los datos nuevamente.'
            ];
            $this->IngresarAutor($alert);
            return;
         }
         
         // 2. Llamar al Modelo
         $result = $this->autorModel->IngresarAutor2($Cutter, $Nombre, $Nacionalidad);

         // 3. Preparar mensaje
         if ($result) {
             $alert = [
                 'type' => 'alert-success',
                 'message' => '<strong>¡Éxito!</strong> El usuario ha sido registrado correctamente.'
             ];
         } else {
             $alert = [
                 'type' => 'alert-warning',
                 'message' => '<strong>Error:</strong> No se pudo registrar el usuario. Verifique si ya existe.'
             ];
         }

         // 4. Volver a cargar la vista con la lista actualizada y el mensaje
         $registros = $this->autorModel->ListarAutor();
         $config = require('../biblioteca-sistema/config/forms/autorform.php');
         extract($config['global']);
         extract($config['insert']);
         
         require_once('../biblioteca-sistema/views/generic/insert.php');
	}

	// Para actualizar 

    public function BuscarAutorByCutter($Cutter){
         $result_Listar = $this->autorModel->BuscarAutorByCutter($Cutter);
         return $result_Listar;
	}

	function UpdateAutor($Cutter = null, $alert = null, $values = null){
         if(!isset($_GET['id'])) {
             // Redirigir o mostrar error si no hay ID
              echo "Error: ID no especificado";
              return;
         }
         $id = $_GET['id'];
         
         // 1. Obtener datos del usuario
         $resultado = $this->autorModel->BuscarAutorByCutter($id);
         $datosUsuario = mysqli_fetch_assoc($resultado);

         // 2. Cargar configuración
         $registros = $this->autorModel->ListarAutor();
         $config = require('../biblioteca-sistema/config/forms/autorform.php');
         extract($config['global']);
         extract($config['update']);
         //Nacionalidad
         // 3. Inyectar valores en los campos del formulario
         foreach ($formFields as &$field) {
            // Caso especial para Cutter de Autor que en BD es Cutter
            if ($field['name'] == 'Cutter' && isset($datosUsuario['Cutter'])) {
                $field['value'] = $datosUsuario['Cutter'];
            }
            elseif (isset($datosUsuario[$field['name']])) {
                $field['value'] = $datosUsuario[$field['name']];
            }
         }
         unset($field); // ROMPEMOS LA REFERENCIA (Importante para que no afecte al siguiente foreach)

         require_once('../biblioteca-sistema/views/generic/update.php');
    }

	public function UpdateAutor2(){
         // 1. Recoger datos del POST
         $Cutter = $_POST['Cutter'];
         $Nombre = $_POST['Nombre'];
         $Nacionalidad = $_POST['Nacionalidad'];

         // 2. Llamar al Modelo (UpdateUser2 en lugar de Ingresar)
         $result = $this->autorModel->UpdateAutor2($Cutter, $Nombre, $Nacionalidad);

         // 3. Preparar mensaje
         if ($result) {
             $alert = [
                 'type' => 'alert-success',
                 'message' => '<strong>¡Éxito!</strong> El usuario ha sido actualizado correctamente.'
             ];
         } else {
             $alert = [
                 'type' => 'alert-warning',
                 'message' => '<strong>Error:</strong> No se pudo actualizar el usuario.'
             ];
         }

         // 4. Volver a cargar la vista con la lista actualizada y el mensaje
         $registros = $this->autorModel->ListarAutor();
         $config = require('../biblioteca-sistema/config/forms/autorform.php');
         extract($config['global']);
         // NOTA: Podríamos redirigir a 'update' de nuevo con el ID, o a 'insert' (lista limpia).
         // Por simplicidad, mandamos al insert/lista general para ver los cambios en la tabla.
         extract($config['insert']); 
         
         require_once('../biblioteca-sistema/views/generic/insert.php');
	}
  
  // Para eliminar

	function DeleteAutor(){
         if(!isset($_GET['id'])) {
              echo "Error: ID no especificado"; return;
         }
         $id = $_GET['id'];
         
         // 1. Llamar al Modelo
         // Nota: Tu modelo tiene DeleteUser($DNI), asumimos que el ID es el DNI
         $result = $this->autorModel->DeleteAutor($id);

         // 3. Volver a cargar la lista
         $registros = $this->autorModel->ListarAutor();
         $config = require('../biblioteca-sistema/config/forms/autorform.php');
         extract($config['global']);         
         require_once('../biblioteca-sistema/views/generic/delete.php'); // O redirigir a donde prefieras
	}
    
    // ==================== BÚSQUEDA AJAX (JSON) ====================

    function BuscarAutorJson(){
        // Endpoint para autocomplete AJAX
        header('Content-Type: application/json');
        $query = isset($_GET['q']) ? $_GET['q'] : '';
        
        if(strlen($query) < 1){
            echo json_encode([]);
            exit;
        }

        $resultados = $this->autorModel->BuscarAutorByQuery($query);
        $data = [];
        while($row = mysqli_fetch_assoc($resultados)){
            $data[] = [
                'id' => $row['Cutter'],
                'text' => $row['Nombre'] . ' - ' . $row['Cutter'],
                'cutter' => $row['Cutter'],
                'nombre' => $row['Nombre'],
                'nacionalidad' => $row['Nacionalidad']
            ];
        }
        echo json_encode($data);
        exit;
    }

    function validarAutor($Cutter = null){
        $valuesRepeat = $this->autorModel->ListarAutor();

         foreach ($valuesRepeat as $value) {
             if ($value['Cutter'] == $Cutter) {
                 return true;
             }
         }
         return false;
    }
}

?>