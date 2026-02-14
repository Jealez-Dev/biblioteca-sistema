<?php

class CatgDeUserController
{

    private $catgDeUserModel;

	function __construct()
	{
        require_once('../biblioteca-sistema/models/CatgDeUserModel.php');
		$this->catgDeUserModel = new CatgDeUserModel();
	}

 	function ListarCatgDeUser(){
         $registros = $this->catgDeUserModel->ListarCatgDeUser();
         $config = require('../biblioteca-sistema/config/forms/catgform.php');
         extract($config['global']);
		 require_once('../biblioteca-sistema/views/generic/list.php');
	}

	public function ListarCatgDeUser1(){
         $result_Listar= $this->catgDeUserModel->ListarCatgDeUser();
         return $result_Listar;
	}
  
	// Para actualizar 

    public function BuscarCatgDeUserById($ID){
         $result_Listar = $this->catgDeUserModel->BuscarCatgDeUserById($ID);
         return $result_Listar;
	}

	function UpdateCatgDeUser($ID = null, $alert = null, $values = null){
         if(!isset($_GET['id'])) {
             // Redirigir o mostrar error si no hay ID
              echo "Error: ID no especificado";
              return;
         }
         $id = $_GET['id'];
         
         // 1. Obtener datos del usuario
         $resultado = $this->catgDeUserModel->BuscarCatgDeUserById($id);
         $datosUsuario = mysqli_fetch_assoc($resultado);

         // 2. Cargar configuración
         $registros = $this->catgDeUserModel->ListarCatgDeUser();
         $config = require('../biblioteca-sistema/config/forms/catgform.php');
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

	public function UpdateCatgDeUser2(){
         // 1. Recoger datos del POST
         $ID = $_POST['ID'];
         $N_de_dias = $_POST['N_de_dias'];
         $N_de_ejemplares = $_POST['N_de_ejemplares'];

         // 2. Llamar al Modelo (UpdateUser2 en lugar de Ingresar)
         $result = $this->catgDeUserModel->UpdateCatgDeUser2($ID, $N_de_dias, $N_de_ejemplares);

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
         $registros = $this->catgDeUserModel->ListarCatgDeUser();
         $config = require('../biblioteca-sistema/config/forms/catgform.php');
         extract($config['global']);
         require_once('../biblioteca-sistema/views/generic/list.php');
	}
}
?>