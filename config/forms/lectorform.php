<?php
return [
    // --- CONFIGURACIÓN GLOBAL (Lista) ---
    'global' => [
        'titulo' => "Listado de Lectores",
        'headers' => ['DNI', 'Carrera_Departamento', 'ID_Catg_de_User_SA'],
        'keys' => ['DNI_Usuario', 'Carrera_Departamento', 'ID_Catg_de_User_SA'],
        'controller' => "Lector",
        'actionUpdate' => "UpdateLector",
        'actionDelete' => "DeleteLector",
        'idField' => "DNI_Usuario"
    ],

    // --- CONFIGURACIÓN INSERTAR ---
    'insert' => [
        'tituloInsert' => "Ingreso de Nuevo Lector",
        'actionInsert' => "IngresarLector2",
        'formFields' => [
            ['label' => 'Seleccione el DNI', 'name' => 'DNI', 'type' => 'select', 'required' => true],
            ['label' => 'ID_Catg_de_User_SA', 'name' => 'ID_Catg_de_User_SA', 'type' => 'select', 'required' => true],
            ['label' => 'Seleccione Carrera', 'name' => 'Carrera', 'type' => 'select', 'required' => true],
            ['label' => 'Seleccione Departamento', 'name' => 'Departamento', 'type' => 'select', 'required' => true]
        ],
    ],

    // --- CONFIGURACIÓN ACTUALIZAR ---
    'update' => [
        'tituloUpdate' => "Actualización de Lector",
        'actionUpdate' => "UpdateLector2",
        'formFields' => [
            ['label' => 'Seleccione el DNI', 'name' => 'DNI', 'type' => 'number', 'required' => true, 'readonly' => true],
            ['label' => 'ID_Catg_de_User_SA', 'name' => 'ID_Catg_de_User_SA', 'type' => 'select', 'required' => true],
            ['label' => 'Seleccione Carrera', 'name' => 'Carrera', 'type' => 'select', 'required' => true],
            ['label' => 'Seleccione Departamento', 'name' => 'Departamento', 'type' => 'select', 'required' => true]
        ],
    ],
];
