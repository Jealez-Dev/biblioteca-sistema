<?php
return [
    // --- CONFIGURACIÓN GLOBAL (Lista) ---
    'global' => [
        'titulo' => "Categorias de Usuarios",
        'headers' => ['ID', 'Tipo', 'N_de_dias', 'N_de_ejemplares'],
        'keys' => ['ID', 'Tipo', 'N_de_dias', 'N_de_ejemplares'],
        'controller' => "CatgDeUser",
        'actionUpdate' => "UpdateCatgDeUser",
        'actionDelete' => "DeleteCatgDeUser",
        'idField' => "ID"
    ],

    // --- CONFIGURACIÓN INSERTAR (Requerido para que no falle el extract en el controlador, aunque no se use) ---
    'insert' => [
        'tituloInsert' => "Ingreso de Categoría",
        'actionInsert' => "IngresarCatgDeUser",
        'formFields' => []
    ],

    // --- CONFIGURACIÓN ACTUALIZAR ---
    'update' => [
        'tituloUpdate' => "Actualización de Categorias de Usuarios",
        'actionUpdate' => "UpdateCatgDeUser2",
        'formFields' => [
            ['label' => 'ID', 'name' => 'ID', 'type' => 'number', 'required' => true, 'readonly' => true],
            ['label' => 'Tipo', 'name' => 'Tipo', 'type' => 'text', 'required' => true, 'readonly' => true],
            ['label' => 'N_de_dias', 'name' => 'N_de_dias', 'type' => 'text', 'required' => true],
            ['label' => 'N_de_ejemplares', 'name' => 'N_de_ejemplares', 'type' => 'text', 'required' => true]
        ],
    ],
];
