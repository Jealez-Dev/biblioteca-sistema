<?php

class UserController
{

    private $userModel;

	function __construct()
	{
        require_once('../biblioteca-sistema/models/UserModel.php');
		$this->userModel = new UserModel();
	}

 	function ListarUser(){
         $registros = $this->userModel->ListarUser();
         $config = require('../biblioteca-sistema/config/forms/userform.php');
         extract($config['global']);
		 require_once('../biblioteca-sistema/views/generic/list.php');
	}

	public function ListarUser1(){
         $result_Listar= $this->userModel->ListarUser();
         return $result_Listar;
	}
  
  // Para insertar

    public function BuscarUltimaUser(){
         $result_Listar = $this->userModel->BuscarUltimaUser();
         return $result_Listar;
	}

	function IngresarUser($alert = null, $values = null){
		 $registros = $this->userModel->ListarUser();
         $config = require('../biblioteca-sistema/config/forms/userform.php');
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

	public function IngresarUser2(){
         // 1. Recoger datos del POST
         // Nota: Idealmente validaríamos que existan
         $DNI = $_POST['DNI'];
         $Nombre = $_POST['Nombre'];
         $Apellido = $_POST['Apellido'];
         $Edad = $_POST['Edad'];
         $Correo = $_POST['Correo'];
         $Num_Telefono = $_POST['Num_Telefono'];

        [$dataExists, $alert, $values] = $this->validarUser($DNI, $Nombre, $Apellido, $Edad, $Correo, $Num_Telefono);

        if($dataExists){
            $this->IngresarUser($alert, $values);
            return;
        }

         // 2. Llamar al Modelo
         $result = $this->userModel->IngresarUser2($Nombre, $Apellido, $Edad, $Correo, $Num_Telefono, $DNI);

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
         $registros = $this->userModel->ListarUser();
         $config = require('../biblioteca-sistema/config/forms/userform.php');
         extract($config['global']);
         extract($config['insert']);
         
         require_once('../biblioteca-sistema/views/generic/insert.php');
	}

	// Para actualizar 

    public function BuscarUserById($DNI){
         $result_Listar = $this->userModel->BuscarUserById($DNI);
         return $result_Listar;
	}

	function UpdateUser($DNI = null, $alert = null, $values = null){
         if(!isset($_GET['id'])) {
             // Redirigir o mostrar error si no hay ID
              echo "Error: ID no especificado";
              $id = $DNI;
         }else{
            $id = $_GET['id'];
         }
         
         // 1. Obtener datos del usuario
         $resultado = $this->userModel->BuscarUserById($id);
         $datosUsuario = mysqli_fetch_assoc($resultado);

         // 2. Cargar configuración
         $registros = $this->userModel->ListarUser();
         $config = require('../biblioteca-sistema/config/forms/userform.php');
         extract($config['global']);
         extract($config['update']);
         
         // 3. Inyectar valores en los campos del formulario
         foreach ($formFields as &$field) {
            if (isset($datosUsuario[$field['name']])) {
                $field['value'] = $datosUsuario[$field['name']];
            }
         }
         unset($field); // ROMPEMOS LA REFERENCIA (Importante para que no afecte al siguiente foreach)

         require_once('../biblioteca-sistema/views/generic/update.php');
    }

	public function UpdateUser2(){
         // 1. Recoger datos del POST
         $DNI = $_POST['DNI'];
         $Nombre = $_POST['Nombre'];
         $Apellido = $_POST['Apellido'];
         $Edad = $_POST['Edad'];
         $Correo = $_POST['Correo'];
         $Num_Telefono = $_POST['Num_Telefono'];

         // 2. Llamar al Modelo (UpdateUser2 en lugar de Ingresar)
         $result = $this->userModel->UpdateUser2($DNI, $Nombre, $Apellido, $Edad, $Correo, $Num_Telefono);

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
         $registros = $this->userModel->ListarUser();
         $config = require('../biblioteca-sistema/config/forms/userform.php');
         extract($config['global']);
         // NOTA: Podríamos redirigir a 'update' de nuevo con el ID, o a 'insert' (lista limpia).
         // Por simplicidad, mandamos al insert/lista general para ver los cambios en la tabla.
         extract($config['insert']); 
         
         require_once('../biblioteca-sistema/views/generic/insert.php');
	}
  
  // Para eliminar

	function DeleteUser(){
         if(!isset($_GET['id'])) {
              echo "Error: ID no especificado"; return;
         }
         $id = $_GET['id'];
         
         // 1. Llamar al Modelo
         // Nota: Tu modelo tiene DeleteUser($DNI), asumimos que el ID es el DNI
         $result = $this->userModel->DeleteUser($id);

         // 3. Volver a cargar la lista
         $registros = $this->userModel->ListarUser();
         $config = require('../biblioteca-sistema/config/forms/userform.php');
         extract($config['global']);         
         require_once('../biblioteca-sistema/views/generic/delete.php'); // O redirigir a donde prefieras
	}
    
    function validarUser($Nombre, $Apellido, $Edad, $Correo, $Num_Telefono, $DNI = null){
        $valuesRepeat = $this->userModel->ListarUser();

         foreach ($valuesRepeat as $value) {
             if ($value['DNI'] == $DNI) {
                 $alert = [
                     'type' => 'alert-warning',
                     'message' => '<strong>Error:</strong> El usuario ya existe. Reingrese los datos nuevamente.'
                 ];
                 $values = [
                     'Nombre' => $Nombre,
                     'Apellido' => $Apellido,
                     'Edad' => $Edad,
                     'Correo' => $Correo,
                     'Num_Telefono' => $Num_Telefono
                 ];
                 return [true, $alert, $values];
             }

             if ($value['Num_Telefono'] == $Num_Telefono) {
                 $alert = [
                     'type' => 'alert-warning',
                     'message' => '<strong>Error:</strong> El número telefonico ya existe. Reingrese los datos nuevamente.'
                 ];
                 $values = [
                     'DNI' => $DNI,
                     'Nombre' => $Nombre,
                     'Apellido' => $Apellido,
                     'Edad' => $Edad,
                     'Correo' => $Correo
                 ];
                 return [true, $alert, $values];
             }

             if ($value['Correo'] == $Correo) {
                 $alert = [
                     'type' => 'alert-warning',
                     'message' => '<strong>Error:</strong> El correo ya existe. Reingrese los datos nuevamente.'
                 ];
                 $values = [
                     'DNI' => $DNI,
                     'Nombre' => $Nombre,
                     'Apellido' => $Apellido,
                     'Edad' => $Edad,
                     'Num_Telefono' => $Num_Telefono
                 ];
                 return [true, $alert, $values];
             }
         }
         return [false, null, null];
    }
}
?>