<?php
return [
    // --- CONFIGURACIÓN GLOBAL (Lista) ---
    'global' => [
        'titulo' => "Listado de Editoriales",
        'headers' => ['ID', 'Nombre', 'Sedematriz', 'Correo', 'Telefono'],
        'keys' => ['ID', 'Nombre', 'SedeMatriz', 'Email', 'Telefono'],
        'controller' => "Editorial",
        'actionUpdate' => "UpdateEditorial",
        'actionDelete' => "DeleteEditorial",
        'idField' => "ID"
    ],

    // --- CONFIGURACIÓN INSERTAR ---
    'insert' => [
        'tituloInsert' => "Ingreso de Nueva Editorial",
        'actionInsert' => "IngresarEditorial2",
        'formFields' => [
            ['label' => 'ID', 'name' => 'ID', 'type' => 'number', 'required' => true],
            ['label' => 'Nombre', 'name' => 'Nombre', 'type' => 'text', 'required' => true],
            ['label' => 'SedeMatriz', 'name' => 'SedeMatriz', 'type' => 'text', 'required' => true],
            ['label' => 'Correo', 'name' => 'Email', 'type' => 'text', 'required' => true],
            ['label' => 'Telefono', 'name' => 'Telefono', 'type' => 'number', 'required' => true]
        ],
    ],

    // --- CONFIGURACIÓN ACTUALIZAR ---
    'update' => [
        'tituloUpdate' => "Actualización de Editorial",
        'actionUpdate' => "UpdateEditorial2",
        'formFields' => [
            ['label' => 'ID', 'name' => 'ID', 'type' => 'number', 'required' => true, 'readonly' => true],
            ['label' => 'Nombre', 'name' => 'Nombre', 'type' => 'text', 'required' => true],
            ['label' => 'SedeMatriz', 'name' => 'SedeMatriz', 'type' => 'text', 'required' => true],
            ['label' => 'Correo', 'name' => 'Email', 'type' => 'text', 'required' => true],
            ['label' => 'Telefono', 'name' => 'Telefono', 'type' => 'number', 'required' => true]
        ],
    ],
];
