<?php

class AdminController
{

    private $adminModel;
    private $userModel;

	function __construct()
	{
        require_once('../biblioteca-sistema/models/AdminModel.php');
        require_once('../biblioteca-sistema/models/UserModel.php');
		$this->adminModel = new AdminModel();
        $this->userModel = new UserModel();
	}

 	function ListarAdmin(){
         $registros = $this->adminModel->ListarAdmin();
         $config = require('../biblioteca-sistema/config/forms/adminform.php');
         extract($config['global']);
		 require_once('../biblioteca-sistema/views/generic/list.php');
	}

	public function ListarAdmin1(){
         $result_Listar= $this->adminModel->ListarAdmin();
         return $result_Listar;
	}
  
  // Para insertar

    public function BuscarUltimaAdmin(){
         $result_Listar = $this->adminModel->BuscarUltimaAdmin();
         return $result_Listar;
	}

	function IngresarAdmin($alert = null){
		 $registros = $this->adminModel->ListarAdmin();
         $config = require('../biblioteca-sistema/config/forms/adminform.php');
         extract($config['global']); // Para la lista
         extract($config['insert']); // Para el form

         // Poblar el select de DNI con usuarios
         $usuarios = $this->userModel->ListarUser();
         $dniOptions = [];
         if($usuarios){
            while($u = mysqli_fetch_assoc($usuarios)){
                $dniOptions[$u['DNI']] = $u['DNI'] . ' - ' . $u['Nombre'] . ' ' . $u['Apellido']; 
            }
         }

         // Inyectar opciones en el campo DNI
         foreach ($formFields as &$field) {
            if ($field['name'] === 'DNI') {
                $field['options'] = $dniOptions;
            }
         }
         unset($field);

		 require_once('../biblioteca-sistema/views/generic/insert.php');
	}

	public function IngresarAdmin2(){
         // 1. Recoger datos del POST
         // Nota: Idealmente validaríamos que existan
         $DNI = $_POST['DNI'];
         $Username = $_POST['Username'];
         $Password = $_POST['Password'];

         $DNI = preg_replace('/\D/', '', $DNI);

        $alert = null;
         if($this->validarAdmin($DNI)){
            $alert = [
                'type' => 'alert-warning',
                'message' => '<strong>Error:</strong> El DNI: '.$DNI.' ya existe. Reingrese los datos nuevamente.'
            ];
            $this->IngresarAdmin($alert);
            return;
         }
         
         // 2. Llamar al Modelo
         $result = $this->adminModel->IngresarAdmin2($DNI, $Username, $Password);

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
         $registros = $this->adminModel->ListarAdmin();
         $config = require('../biblioteca-sistema/config/forms/adminform.php');
         extract($config['global']);
         extract($config['insert']);
         
         require_once('../biblioteca-sistema/views/generic/insert.php');
	}

	// Para actualizar 

    public function BuscarAdminById($DNI){
         $result_Listar = $this->adminModel->BuscarAdminById($DNI);
         return $result_Listar;
	}

	function UpdateAdmin($DNI = null, $alert = null, $values = null){
         if(!isset($_GET['id'])) {
             // Redirigir o mostrar error si no hay ID
              echo "Error: ID no especificado";
              return;
         }
         $id = $_GET['id'];
         
         // 1. Obtener datos del usuario
         $resultado = $this->adminModel->BuscarAdminById($id);
         $datosUsuario = mysqli_fetch_assoc($resultado);

         // 2. Cargar configuración
         $registros = $this->adminModel->ListarAdmin();
         $config = require('../biblioteca-sistema/config/forms/adminform.php');
         extract($config['global']);
         extract($config['update']);
         
         // 3. Inyectar valores en los campos del formulario
         foreach ($formFields as &$field) {
            // Caso especial para DNI de Admin que en BD es DNI_Usuario
            if ($field['name'] == 'DNI' && isset($datosUsuario['DNI_Usuario'])) {
                $field['value'] = $datosUsuario['DNI_Usuario'];
            }
            elseif (isset($datosUsuario[$field['name']])) {
                $field['value'] = $datosUsuario[$field['name']];
            }
         }
         unset($field); // ROMPEMOS LA REFERENCIA (Importante para que no afecte al siguiente foreach)

         require_once('../biblioteca-sistema/views/generic/update.php');
    }

	public function UpdateAdmin2(){
         // 1. Recoger datos del POST
         $DNI = $_POST['DNI'];
         $Username = $_POST['Username'];
         $Password = $_POST['Password'];

         // 2. Llamar al Modelo (UpdateUser2 en lugar de Ingresar)
         $result = $this->adminModel->UpdateAdmin2($DNI, $Username, $Password);

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
         $registros = $this->adminModel->ListarAdmin();
         $config = require('../biblioteca-sistema/config/forms/adminform.php');
         extract($config['global']);
         // NOTA: Podríamos redirigir a 'update' de nuevo con el ID, o a 'insert' (lista limpia).
         // Por simplicidad, mandamos al insert/lista general para ver los cambios en la tabla.
         extract($config['insert']); 
         
         require_once('../biblioteca-sistema/views/generic/insert.php');
	}
  
  // Para eliminar

	function DeleteAdmin(){
         if(!isset($_GET['id'])) {
              echo "Error: ID no especificado"; return;
         }
         $id = $_GET['id'];
         
         // 1. Llamar al Modelo
         // Nota: Tu modelo tiene DeleteUser($DNI), asumimos que el ID es el DNI
         $result = $this->adminModel->DeleteAdmin($id);

         // 3. Volver a cargar la lista
         $registros = $this->adminModel->ListarAdmin();
         $config = require('../biblioteca-sistema/config/forms/adminform.php');
         extract($config['global']);         
         require_once('../biblioteca-sistema/views/generic/delete.php'); // O redirigir a donde prefieras
	}
    
    function validarAdmin($DNI = null){
        $valuesRepeat = $this->adminModel->ListarAdmin();

         foreach ($valuesRepeat as $value) {
             if ($value['DNI_Usuario'] == $DNI) {
                 return true;
             }
         }
         return false;
    }
}
?>