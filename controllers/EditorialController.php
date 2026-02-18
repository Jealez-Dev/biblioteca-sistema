<?php

class EditorialController
{
    public $EditorialModel;

	function __construct()
	{
		require_once('../biblioteca-sistema/models/EditorialModel.php');
		$this->EditorialModel = new EditorialModel();
	}

 	function ListarEditorial(){
		 $registros = $this->EditorialModel->ListarEditorial();
         $config = require('../biblioteca-sistema/config/forms/Editorialform.php');
         extract($config['global']);
		 require_once('../biblioteca-sistema/views/generic/list.php');
	}

	 public function ListarEditorial1(){
         $result_Listar= $this->EditorialModel->ListarEditorial();
         return $result_Listar;
	}
  
  // Para insertar

     public function BuscarUltimaEditorial(){
    	 require_once('models/EditorialModel.php');
         $result_Listar = $this->EditorialModel->BuscarUltimaEditorial();
         return $result_Listar;
	}

	function IngresarEditorial($alert = null, $values = null){
		 $registros = $this->EditorialModel->ListarEditorial();
         $config = require('../biblioteca-sistema/config/forms/Editorialform.php');
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

	 public function IngresarEditorial2(){
	  	  // 1. Recoger datos del POST
         // Nota: Idealmente validaríamos que existan
         $id = $_POST['ID'];
         $nombre = $_POST['Nombre'];
         $sedeMatriz = $_POST['SedeMatriz'];
         $email = $_POST['Email'];
         $telefono = $_POST['Telefono'];

        [$dataExists, $alert, $values] = $this->validaEditorial($id, $nombre, $sedeMatriz, $email, $telefono);

        if($dataExists){
            $this->IngresarEditorial($alert, $values);
            return;
        }

         // 2. Llamar al Modelo
         $result = $this->EditorialModel->IngresarEditorial2( $id, $nombre, $sedeMatriz, $email, $telefono);

         // 3. Preparar mensaje
         if ($result) {
             $alert = [
                 'type' => 'alert-success',
                 'message' => '<strong>¡Éxito!</strong> La editorial ha sido registrado correctamente.'
             ];
         } else {
             $alert = [
                 'type' => 'alert-warning',
                 'message' => '<strong>Error:</strong> No se pudo registrar la editorial. Verifique si ya existe.'
             ];
	   }
         // 4. Volver a cargar la vista con la lista actualizada y el mensaje
         $registros = $this->EditorialModel->ListarEditorial();
         $config = require('../biblioteca-sistema/config/forms/Editorialform.php');
         extract($config['global']);
         extract($config['insert']);
         
         require_once('../biblioteca-sistema/views/generic/insert.php');
	}


	// Para actualizar 

     public function BuscarEditorialById($id){
    	 require_once('models/EditorialModel.php');
         $result_Listar = $this->EditorialModel->BuscarEditorialById($id);
         return $result_Listar;
	}

	function UpdateEditorial($id = null, $alert = null, $values = null){
         if(!isset($_GET['id'])) {
             // Redirigir o mostrar error si no hay ID
              echo "Error: ID no especificado";
              $id = $id;
         }else{
            $id = $_GET['id'];
         }
         
         // 1. Obtener datos de la Editorial
         $resultado = $this->EditorialModel->BuscarEditorialById($id);
         $datosEditorial = mysqli_fetch_assoc($resultado);

         // 2. Cargar configuración
         $registros = $this->EditorialModel->ListarEditorial();
         $config = require('../biblioteca-sistema/config/forms/Editorialform.php');
         extract($config['global']);
         extract($config['update']);
         
         // 3. Inyectar valores en los campos del formulario
         foreach ($formFields as &$field) {
            if (isset($datosEditorial[$field['name']])) {
                $field['value'] = $datosEditorial[$field['name']];
            }
         }
         unset($field); // ROMPEMOS LA REFERENCIA (Importante para que no afecte al siguiente foreach)

         require_once('../biblioteca-sistema/views/generic/update.php');
    }


	 public function UpdateEditorial2(){
	  	     // 1. Recoger datos del POST
         $id = $_POST['ID'];
         $nombre = $_POST['Nombre'];
         $sedeMatriz = $_POST['SedeMatriz'];
         $email = $_POST['Email'];
         $telefono = $_POST['Telefono'];

         // 2. Llamar al Modelo (UpdateEditorial2 en lugar de Ingresar)
         $result = $this->EditorialModel->UpdateEditorial2($id, $nombre, $sedeMatriz, $email, $telefono);

         // 3. Preparar mensaje
         if ($result) {
             $alert = [
                 'type' => 'alert-success',
                 'message' => '<strong>¡Éxito!</strong> La editorial ha sido actualizada correctamente.'
             ];
         } else {
             $alert = [
                 'type' => 'alert-warning',
                 'message' => '<strong>Error:</strong> No se pudo actualizar la editorial.'
             ];
         }

         // 4. Volver a cargar la vista con la lista actualizada y el mensaje
         $registros = $this->EditorialModel->ListarEditorial();
         $config = require('../biblioteca-sistema/config/forms/Editorialform.php');
         extract($config['global']);
         // NOTA: Podríamos redirigir a 'update' de nuevo con el ID, o a 'insert' (lista limpia).
         // Por simplicidad, mandamos al insert/lista general para ver los cambios en la tabla.
         extract($config['insert']); 
         
         require_once('../biblioteca-sistema/views/generic/insert.php');
	}
  
  // Para eliminar

	function DeleteEditorial(){
		 if(!isset($_GET['id'])) {
              echo "Error: ID no especificado"; return;
         }
         $id = $_GET['id'];
         
         // 1. Llamar al Modelo
         $result = $this->EditorialModel->DeleteEditorial($id);

         // 2. Volver a cargar la lista
         $registros = $this->EditorialModel->ListarEditorial();
         $config = require('../biblioteca-sistema/config/forms/Editorialform.php');
         extract($config['global']);         
         require_once('../biblioteca-sistema/views/generic/delete.php'); // O redirigir a donde prefieras
	}

    // ==================== BÚSQUEDA AJAX (JSON) ====================

    function BuscarEditorialJson(){
        // Endpoint para autocomplete AJAX
        header('Content-Type: application/json');
        $query = isset($_GET['q']) ? $_GET['q'] : '';
        
        if(strlen($query) < 1){
            echo json_encode([]);
            exit;
        }

        $resultados = $this->EditorialModel->BuscarEditorialByQuery($query);
        $data = [];
        while($row = mysqli_fetch_assoc($resultados)){
            $data[] = [
                'id' => $row['ID'],
                'text' => $row['Nombre'] . ' - ID: ' . $row['ID'],
                'nombre' => $row['Nombre'],
                'sedeMatriz' => $row['SedeMatriz']
            ];
        }
        echo json_encode($data);
        exit;
    }

    function validaEditorial($id, $nombre, $sedeMatriz, $email, $telefono = null){
        $valuesRepeat = $this->EditorialModel->ListarEditorial();
        $existe = $this->EditorialModel->BuscarEditorialById($id);

         foreach ($valuesRepeat as $value) {
             if ($value['ID'] == $id) {
                 $alert = [
                     'type' => 'alert-warning',
                     'message' => '<strong>Error:</strong> La editorial ya ha sido registrada anteriormente. Reingrese los datos nuevamente.'
                 ];
                 $values = [
                     'Nombre' => $nombre,
                     'Sedematriz' => $sedeMatriz,
                     'Correo' => $email,
                     'Telefono' => $telefono
                 ];
                 return [true, $alert, $values];
             }



             if ($value['Telefono'] == $telefono) {
                 $alert = [
                     'type' => 'alert-warning',
                     'message' => '<strong>Error:</strong> El número telefonico ya existe. Reingrese los datos nuevamente.'
                 ];
                 $values = [
                     'ID' => $id,
                     'Nombre' => $nombre,
                     'Sedematriz' => $sedeMatriz,
                     'Correo' => $email
                 ];
                 return [true, $alert, $values];
             }

             if ($value['Email'] == $email) {
                 $alert = [
                     'type' => 'alert-warning',
                     'message' => '<strong>Error:</strong> El correo ya existe. Reingrese los datos nuevamente.'
                 ];
                 $values = [
                     'ID' => $id,
                     'Nombre' => $nombre,
                     'Sedematriz' => $sedeMatriz,
                     'Telefono' => $telefono
                 ];
                 return [true, $alert, $values];
             }
         }
         return [false, null, null];
    }
}
?>