<?php

class ObraController
{
    private $obraModel;

	function __construct()
	{
        require_once('../biblioteca-sistema/models/ObraModel.php');
		$this->obraModel = new ObraModel();
	}

    // ==================== HELPER: CARGAR OPCIONES DE DATALIST ====================
    // Carga todos los registros de las entidades relacionadas y los inyecta
    // como 'options' en los campos tipo datalist del formulario
    
    private function inyectarOpcionesDatalist(&$formFields) {
        // Cargar datos de entidades relacionadas
        require_once('../biblioteca-sistema/models/AutorModel.php');
        require_once('../biblioteca-sistema/models/CddModel.php');
        require_once('../biblioteca-sistema/models/EditorialModel.php');
        require_once('../biblioteca-sistema/models/TObrasModel.php');

        $autores = AutorModel::ListarAutor();
        $cdds = CddModel::ListarCdd();
        $editoriales = EditorialModel::ListarEditorial();
        $tobras = TObrasModel::ListarTObras();

        // Construir arrays asociativos [id => texto_display]
        $opcionesAutor = [];
        while ($row = mysqli_fetch_assoc($autores)) {
            $opcionesAutor[$row['Cutter']] = $row['Nombre'] . ' - ' . $row['Cutter'];
        }

        $opcionesCdd = [];
        while ($row = mysqli_fetch_assoc($cdds)) {
            $opcionesCdd[$row['Codigo']] = $row['Codigo'] . ' - ' . $row['Descripcion'];
        }

        $opcionesEditorial = [];
        while ($row = mysqli_fetch_assoc($editoriales)) {
            $opcionesEditorial[$row['ID']] = $row['Nombre'] . ' - ID: ' . $row['ID'];
        }

        $opcionesTObras = [];
        while ($row = mysqli_fetch_assoc($tobras)) {
            $opcionesTObras[$row['ID']] = $row['Descripcion'] . ' - ID: ' . $row['ID'];
        }

        // Inyectar en los campos del formulario según el nombre del campo
        foreach ($formFields as &$field) {
            if ($field['name'] === 'Cutter_Autor_display') {
                $field['options'] = $opcionesAutor;
            } elseif ($field['name'] === 'Codigo_CDD_display') {
                $field['options'] = $opcionesCdd;
            } elseif ($field['name'] === 'ID_Editorial_display') {
                $field['options'] = $opcionesEditorial;
            } elseif ($field['name'] === 'ID_T_Obras_display') {
                $field['options'] = $opcionesTObras;
            }
        }
        unset($field);
    }

    // ==================== LISTAR (READ) ====================

 	function ListarObra(){
         $registros = $this->obraModel->ListarObra();
         $config = require('../biblioteca-sistema/config/forms/obraform.php');
         extract($config['global']);
		 require_once('../biblioteca-sistema/views/generic/list.php');
	}

	public function ListarObra1(){
         $result_Listar= $this->obraModel->ListarObra();
         return $result_Listar;
	}

    // ==================== INSERTAR (CREATE) ====================

	function IngresarObra($alert = null, $values = null){
		 $registros = $this->obraModel->ListarObra();
         $config = require('../biblioteca-sistema/config/forms/obraform.php');
         extract($config['global']); // Para la lista
         extract($config['insert']); // Para el form

         // Restaurar estado de sesión si viene de "Crear nuevo" sub-entidad
         if (isset($_SESSION['obra_form_state']) && $values === null) {
             $values = $_SESSION['obra_form_state'];
             unset($_SESSION['obra_form_state']);
         }

         if ($values) {
            foreach ($formFields as &$field) {
                if (isset($values[$field['name']])) {
                    $field['value'] = $values[$field['name']];
                }
            }
            unset($field);
         }

         // Inyectar opciones del datalist desde la BD
         $this->inyectarOpcionesDatalist($formFields);

		 require_once('../biblioteca-sistema/views/generic/insert.php');
	}

	public function IngresarObra2(){
         // 1. Recoger datos del POST
         $anio = $_POST['Anio'];
         $cutterAutor = $_POST['Cutter_Autor'];
         $codigoCdd = $_POST['Codigo_CDD'];
         $titulo = $_POST['Titulo'];
         $edicion = $_POST['Edicion'];
         $otrosAutores = $_POST['Otros_Autores'];
         $isbn = $_POST['ISBN'];
         $isnn = $_POST['ISNN'];
         $descripcion = $_POST['Descripcion'];
         $lPublicacion = $_POST['L_Publicacion'];
         $nDepLegal = $_POST['N_Dep_Legal'];
         $idEditorial = $_POST['ID_Editorial'];
         $idTObras = $_POST['ID_T_Obras'];

         // Validación backend: todos los campos requeridos
         $campos = [$anio, $cutterAutor, $codigoCdd, $titulo, $edicion, $otrosAutores, 
                    $isbn, $isnn, $descripcion, $lPublicacion, $nDepLegal, $idEditorial, $idTObras];
         
         foreach ($campos as $campo) {
             if (empty(trim($campo))) {
                $alert = [
                     'type' => 'alert-warning',
                     'message' => '<strong>Error:</strong> Todos los campos son obligatorios. Verifique los datos.'
                 ];
                 $values = $_POST;
                 $this->IngresarObra($alert, $values);
                 return;
             }
         }

         // Verificar que no exista una obra con la misma clave compuesta
         $existe = $this->obraModel->BuscarObraById($anio, $cutterAutor, $codigoCdd);
         if (mysqli_num_rows($existe) > 0) {
             $alert = [
                 'type' => 'alert-warning',
                 'message' => '<strong>Error:</strong> Ya existe una obra con ese Año, Autor y CDD. Reingrese los datos.'
             ];
             $values = $_POST;
             $this->IngresarObra($alert, $values);
             return;
         }

         // 2. Llamar al Modelo
         $result = $this->obraModel->IngresarObra2(
             $anio, $cutterAutor, $codigoCdd, $titulo, $edicion,
             $otrosAutores, $isbn, $isnn, $descripcion,
             $lPublicacion, $nDepLegal, $idEditorial, $idTObras
         );

         // 3. Preparar mensaje
         if ($result) {
             $alert = [
                 'type' => 'alert-success',
                 'message' => '<strong>¡Éxito!</strong> La obra ha sido registrada correctamente.'
             ];
         } else {
             $alert = [
                 'type' => 'alert-warning',
                 'message' => '<strong>Error:</strong> No se pudo registrar la obra. Verifique los datos e intente nuevamente.'
             ];
         }

         // 4. Volver a cargar la vista con la lista actualizada y el mensaje
         $registros = $this->obraModel->ListarObra();
         $config = require('../biblioteca-sistema/config/forms/obraform.php');
         extract($config['global']);
         extract($config['insert']);
         
         // Inyectar opciones del datalist
         $this->inyectarOpcionesDatalist($formFields);
         
         require_once('../biblioteca-sistema/views/generic/insert.php');
	}

    // ==================== ACTUALIZAR (UPDATE) ====================

	function UpdateObra($alert = null, $values = null){
         if(!isset($_GET['id'])) {
               echo "Error: Clave no especificada";
               return;
         }

         // La clave compuesta se pasa como: id=Año&cutter=Cutter_Autor&cdd=Codigo_CDD
         $anio = $_GET['id'];
         $cutter = isset($_GET['cutter']) ? $_GET['cutter'] : '';
         $codigoCdd = isset($_GET['cdd']) ? $_GET['cdd'] : '';
         
         // 1. Obtener datos de la Obra
         $resultado = $this->obraModel->BuscarObraById($anio, $cutter, $codigoCdd);
         $datosObra = mysqli_fetch_assoc($resultado);

         if (!$datosObra) {
             echo "Error: Obra no encontrada";
             return;
         }

         // 2. Cargar configuración
         $registros = $this->obraModel->ListarObra();
         $config = require('../biblioteca-sistema/config/forms/obraform.php');
         extract($config['global']);
         extract($config['update']);
         
         // 3. Inyectar opciones del datalist
         $this->inyectarOpcionesDatalist($formFields);

         // 4. Inyectar valores en los campos del formulario
         foreach ($formFields as &$field) {
             // Mapeo especial: el campo 'Anio' en el form corresponde a 'Año' en la BD
             if ($field['name'] == 'Anio' && isset($datosObra['Año'])) {
                 $field['value'] = $datosObra['Año'];
             }
             // Campos de display de datalist: mostrar texto descriptivo
             elseif ($field['name'] == 'Cutter_Autor_display' && isset($datosObra['Nombre_Autor'])) {
                 $field['value'] = $datosObra['Nombre_Autor'] . ' - ' . $datosObra['Cutter_Autor'];
             }
             elseif ($field['name'] == 'Codigo_CDD_display' && isset($datosObra['Desc_CDD'])) {
                 $field['value'] = $datosObra['Codigo_CDD'] . ' - ' . $datosObra['Desc_CDD'];
             }
             elseif ($field['name'] == 'ID_Editorial_display' && isset($datosObra['Nombre_Editorial'])) {
                 $field['value'] = $datosObra['Nombre_Editorial'] . ' - ID: ' . $datosObra['ID_Editorial'];
             }
             elseif ($field['name'] == 'ID_T_Obras_display' && isset($datosObra['Desc_TObras'])) {
                 $field['value'] = $datosObra['ID_T_Obras'] . ' - ' . $datosObra['Desc_TObras'];
             }
             // Campos ocultos y campos normales
             elseif (isset($datosObra[$field['name']])) {
                 $field['value'] = $datosObra[$field['name']];
             }
         }
         unset($field);

         require_once('../biblioteca-sistema/views/generic/update.php');
    }

	public function UpdateObra2(){
         // 1. Recoger datos del POST
         $anio = $_POST['Anio'];
         $cutterAutor = $_POST['Cutter_Autor'];
         $codigoCdd = $_POST['Codigo_CDD'];
         $titulo = $_POST['Titulo'];
         $edicion = $_POST['Edicion'];
         $otrosAutores = $_POST['Otros_Autores'];
         $isbn = $_POST['ISBN'];
         $isnn = $_POST['ISNN'];
         $descripcion = $_POST['Descripcion'];
         $lPublicacion = $_POST['L_Publicacion'];
         $nDepLegal = $_POST['N_Dep_Legal'];
         $idEditorial = $_POST['ID_Editorial'];
         $idTObras = $_POST['ID_T_Obras'];

         // 2. Llamar al Modelo
         $result = $this->obraModel->UpdateObra2(
             $anio, $cutterAutor, $codigoCdd, $titulo, $edicion,
             $otrosAutores, $isbn, $isnn, $descripcion,
             $lPublicacion, $nDepLegal, $idEditorial, $idTObras
         );

         // 3. Preparar mensaje
         if ($result) {
             $alert = [
                 'type' => 'alert-success',
                 'message' => '<strong>¡Éxito!</strong> La obra ha sido actualizada correctamente.'
             ];
         } else {
             $alert = [
                 'type' => 'alert-warning',
                 'message' => '<strong>Error:</strong> No se pudo actualizar la obra.'
             ];
         }

         // 4. Volver a cargar la vista
         $registros = $this->obraModel->ListarObra();
         $config = require('../biblioteca-sistema/config/forms/obraform.php');
         extract($config['global']);
         extract($config['insert']); 
         
         // Inyectar opciones del datalist
         $this->inyectarOpcionesDatalist($formFields);
         
         require_once('../biblioteca-sistema/views/generic/insert.php');
	}

    // ==================== ELIMINAR (DELETE) ====================

	function DeleteObra(){
         if(!isset($_GET['id'])) {
              echo "Error: Clave no especificada"; return;
         }
         
         // Clave compuesta
         $anio = $_GET['id'];
         $cutter = isset($_GET['cutter']) ? $_GET['cutter'] : '';
         $codigoCdd = isset($_GET['cdd']) ? $_GET['cdd'] : '';
         
         // 1. Llamar al Modelo
         $result = $this->obraModel->DeleteObra($anio, $cutter, $codigoCdd);

         // 2. Volver a cargar la lista
         $registros = $this->obraModel->ListarObra();
         $config = require('../biblioteca-sistema/config/forms/obraform.php');
         extract($config['global']);         
         require_once('../biblioteca-sistema/views/generic/delete.php');
	}

    // ==================== GUARDAR ESTADO PARA "CREAR NUEVO" ====================
    
    function GuardarEstadoObra(){
        // Guarda el estado actual del formulario en la sesión
        // para que al volver de crear un sub-entidad, se restaure
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['obra_form_state'] = $_POST;
        }
        
        // Redirigir a la entidad que se quiere crear
        $entity = isset($_GET['entity']) ? $_GET['entity'] : '';
        
        switch($entity) {
            case 'autor':
                header('Location: ?controller=Autor&action=IngresarAutor&returnTo=Obra');
                break;
            case 'editorial':
                header('Location: ?controller=Editorial&action=IngresarEditorial&returnTo=Obra');
                break;
            case 'cdd':
                header('Location: ?controller=Cdd&action=IngresarCdd&returnTo=Obra');
                break;
            case 'tobras':
                header('Location: ?controller=TObras&action=IngresarTObras&returnTo=Obra');
                break;
            default:
                header('Location: ?controller=Obra&action=IngresarObra');
                break;
        }
        exit;
    }
}
?>
