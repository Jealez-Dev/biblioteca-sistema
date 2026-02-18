<?php

class EstadoController
{
    private $model;

	function __construct()
	{
        require_once('../biblioteca-sistema/models/EstadoModel.php');
		$this->model = new EstadoModel();
	}

 	function ListarEstado(){
         $registros = $this->model->ListarEstado();
         $config = require('../biblioteca-sistema/config/forms/estadoform.php');
         extract($config['global']);
		 require_once('../biblioteca-sistema/views/generic/list.php');
	}
}
?>
