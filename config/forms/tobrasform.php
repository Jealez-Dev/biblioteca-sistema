<?php
return [
    // --- CONFIGURACIÓN GLOBAL (Lista) ---
    'global' => [
        'titulo' => "Listado de Tipos de Obras",
        'headers' => ['ID', 'Descripción', 'Prestar'],
        'keys' => ['ID', 'Descripcion', 'Prestar'],
        'controller' => "TObras",
        'actionUpdate' => "UpdateTObras",
        'actionDelete' => "DeleteTObras",
        'idField' => "ID"
    ],

    // --- CONFIGURACIÓN INSERTAR ---
    'insert' => [
        'tituloInsert' => "Ingreso de Nuevo Tipo de Obra",
        'actionInsert' => "IngresarTObras2",
        'formFields' => [
            ['label' => 'ID', 'name' => 'ID', 'type' => 'number', 'required' => true],
            ['label' => 'Descripción', 'name' => 'Descripcion', 'type' => 'text', 'required' => true],
            ['label' => 'Prestar', 'name' => 'Prestar', 'type' => 'select', 'required' => true, 'options' => ['1' => 'Sí', '0' => 'No']]
        ],
    ],

    // --- CONFIGURACIÓN ACTUALIZAR ---
    'update' => [
        'tituloUpdate' => "Actualización de Tipo de Obra",
        'actionUpdate' => "UpdateTObras2",
        'formFields' => [
            ['label' => 'ID', 'name' => 'ID', 'type' => 'number', 'required' => true, 'readonly' => true],
            ['label' => 'Descripción', 'name' => 'Descripcion', 'type' => 'text', 'required' => true],
            ['label' => 'Prestar', 'name' => 'Prestar', 'type' => 'select', 'required' => true, 'options' => ['1' => 'Sí', '0' => 'No']]
        ],
    ],
];
