<?php

class LectorController
{

    private $lectorModel;
    private $userModel;
    private $Carreras;
    private $Departamentos;
    private $CatgDeUser;

	function __construct()
	{
        require_once('../biblioteca-sistema/models/LectorModel.php');
        require_once('../biblioteca-sistema/models/UserModel.php');
        require_once('../biblioteca-sistema/models/CatgDeUserModel.php');
		$this->lectorModel = new LectorModel();
        $this->userModel = new UserModel();
        $this->CatgDeUser = new CatgDeUserModel();
        $this->Carreras = ["Medicina","Enfermería","Hotelería y Turismo", "Ciencias Aplicadas del Mar", "Administración", "Informática"];
        $this->Departamentos = ["Medicina","Enfermería","Hotelería y Turismo", "Ciencias Aplicadas del Mar", "Administración", "Informática"];
	}

 	function ListarLector(){
         $registros = $this->lectorModel->ListarLector();
         $config = require('../biblioteca-sistema/config/forms/lectorform.php');
         extract($config['global']);
		 require_once('../biblioteca-sistema/views/generic/list.php');
	}

	public function ListarLector1(){
         $result_Listar= $this->lectorModel->ListarLector();
         return $result_Listar;
	}
  
  // Para insertar

    public function BuscarUltimaLector(){
         $result_Listar = $this->lectorModel->BuscarUltimaLector();
         return $result_Listar;
	}

	function IngresarLector($alert = null){
		 $registros = $this->lectorModel->ListarLector();
         $config = require('../biblioteca-sistema/config/forms/lectorform.php');
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

         $CatgDeUser = $this->CatgDeUser->ListarCatgDeUser();
         $catgDeUserOptions = [];
         if($CatgDeUser){
            while($c = mysqli_fetch_assoc($CatgDeUser)){
                $catgDeUserOptions[$c['ID']] = $c['ID'] . ' - ' . $c['Tipo']; 
            }
         }

         // Inyectar opciones en el campo DNI
         foreach ($formFields as &$field) {
            if ($field['name'] === 'DNI') {
                $field['options'] = $dniOptions;
            }
            if ($field['name'] === 'Carrera') {
                $field['options'] = $this->Carreras;
            }
            if ($field['name'] === 'Departamento') {
                $field['options'] = $this->Departamentos;
            }
            if ($field['name'] === 'ID_Catg_de_User_SA') {
                $field['options'] = $catgDeUserOptions;
            }
         }
         
         unset($field);

		 require_once('../biblioteca-sistema/views/generic/insert.php');
	}

	public function IngresarLector2(){
         // 1. Recoger datos del POST
         // Nota: Idealmente validaríamos que existan
         $DNI = $_POST['DNI'];
         $Carrera_Departamento = $_POST['Carrera'] ?? $_POST['Departamento'] ?? null;
         $ID_Catg_de_User_SA = $_POST['ID_Catg_de_User_SA'];

         $DNI = preg_replace('/\D/', '', $DNI);
         $ID_Catg_de_User_SA = preg_replace('/[^0-9]/', '', $ID_Catg_de_User_SA);

        $alert = null;
         if($this->validarLector($DNI)){
            $alert = [
                'type' => 'alert-warning',
                'message' => '<strong>Error:</strong> El DNI: '.$DNI.' ya existe. Reingrese los datos nuevamente.'
            ];
            $this->IngresarLector($alert);
            return;
         }
         
         // 2. Llamar al Modelo
         $result = $this->lectorModel->IngresarLector2($DNI, $Carrera_Departamento, $ID_Catg_de_User_SA);

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
         $registros = $this->lectorModel->ListarLector();
         $config = require('../biblioteca-sistema/config/forms/lectorform.php');
         extract($config['global']);
         extract($config['insert']);
         
         require_once('../biblioteca-sistema/views/generic/insert.php');
	}

	// Para actualizar 

    public function BuscarLectorById($DNI){
         $result_Listar = $this->lectorModel->BuscarLectorById($DNI);
         return $result_Listar;
	}

	function UpdateLector($DNI = null, $alert = null, $values = null){
         if(!isset($_GET['id'])) {
             // Redirigir o mostrar error si no hay ID
              echo "Error: ID no especificado";
              return;
         }
         $id = $_GET['id'];
         
         // 1. Obtener datos del usuario
         $resultado = $this->lectorModel->BuscarLectorById($id);
         $datosUsuario = mysqli_fetch_assoc($resultado);

         // 2. Cargar configuración
         $registros = $this->lectorModel->ListarLector();
         $config = require('../biblioteca-sistema/config/forms/lectorform.php');
         extract($config['global']);
         extract($config['update']);
         
         // 3. Inyectar valores en los campos del formulario
         // 2.b Cargar opciones para el select de categorias (Igual que en Insert)
         $CatgDeUser = $this->CatgDeUser->ListarCatgDeUser();
         $catgDeUserOptions = [];
         if($CatgDeUser){
            while($c = mysqli_fetch_assoc($CatgDeUser)){
                $catgDeUserOptions[$c['ID']] = $c['ID'] . ' - ' . $c['Tipo']; 
            }
         }

         // 3. Inyectar valores en los campos del formulario
         foreach ($formFields as &$field) {
            // Inyectamos el VALOR actual de la base de datos
            // Caso especial: DNI en el form se llama 'DNI', pero en BD 'DNI_Usuario'
            if ($field['name'] == 'DNI' && isset($datosUsuario['DNI_Usuario'])) {
                $field['value'] = $datosUsuario['DNI_Usuario'];
            }
            elseif (isset($datosUsuario[$field['name']])) {
                $field['value'] = $datosUsuario[$field['name']];
            }

            // Inyectamos las OPCIONES para los selects
            if ($field['name'] === 'Carrera') {
                $field['options'] = $this->Carreras;
            }
            if ($field['name'] === 'Departamento') {
                $field['options'] = $this->Departamentos;
            }
            if ($field['name'] === 'ID_Catg_de_User_SA') {
                $field['options'] = $catgDeUserOptions;
            }
         }
         unset($field); // ROMPEMOS LA REFERENCIA

         require_once('../biblioteca-sistema/views/generic/update.php');
    }

	public function UpdateLector2(){
         // 1. Recoger datos del POST
         $DNI = $_POST['DNI'];
         $Carrera_Departamento = $_POST['Carrera'] ?? $_POST['Departamento'] ?? null;
         $ID_Catg_de_User_SA = $_POST['ID_Catg_de_User_SA'];

         $DNI = preg_replace('/\D/', '', $DNI);
         $ID_Catg_de_User_SA = preg_replace('/[^0-9]/', '', $ID_Catg_de_User_SA);

         // 2. Llamar al Modelo (UpdateUser2 en lugar de Ingresar)
         $result = $this->lectorModel->UpdateLector2($DNI, $Carrera_Departamento, $ID_Catg_de_User_SA);

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
         $registros = $this->lectorModel->ListarLector();
         $config = require('../biblioteca-sistema/config/forms/lectorform.php');
         extract($config['global']);
         
         require_once('../biblioteca-sistema/views/generic/list.php');
	}
  
  // Para eliminar

	function DeleteLector(){
         if(!isset($_GET['id'])) {
              echo "Error: ID no especificado"; return;
         }
         $id = $_GET['id'];
         
         // 1. Llamar al Modelo
         // Nota: Tu modelo tiene DeleteUser($DNI), asumimos que el ID es el DNI
         $result = $this->lectorModel->DeleteLector($id);

         // 3. Volver a cargar la lista
         $registros = $this->lectorModel->ListarLector();
         $config = require('../biblioteca-sistema/config/forms/lectorform.php');
         extract($config['global']);         
         require_once('../biblioteca-sistema/views/generic/delete.php'); // O redirigir a donde prefieras
	}
    
    function validarLector($DNI = null){
        $valuesRepeat = $this->lectorModel->ListarLector();

         foreach ($valuesRepeat as $value) {
             if ($value['DNI_Usuario'] == $DNI) {
                 return true;
             }
         }
         return false;
    }
}
?>