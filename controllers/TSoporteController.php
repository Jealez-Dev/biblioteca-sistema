<?php

class TSoporteController
{
    private $model;

	function __construct()
	{
        require_once('../biblioteca-sistema/models/TSoporteModel.php');
		$this->model = new TSoporteModel();
	}

 	function ListarTSoporte(){
         $registros = $this->model->ListarTSoporte();
         $config = require('../biblioteca-sistema/config/forms/tsoporteform.php');
         extract($config['global']);
		 require_once('../biblioteca-sistema/views/generic/list.php');
	}
}
?>
