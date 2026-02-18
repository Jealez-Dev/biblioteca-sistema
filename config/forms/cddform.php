<?php
return [
    // --- CONFIGURACIÓN GLOBAL (Lista) ---
    'global' => [
        'titulo' => "Listado de Clasificación Decimal (CDD)",
        'headers' => ['Código', 'Descripción'],
        'keys' => ['Codigo', 'Descripcion'],
        'controller' => "Cdd",
        'actionUpdate' => "UpdateCdd",
        'actionDelete' => "DeleteCdd",
        'idField' => "Codigo"
    ],

    // --- CONFIGURACIÓN INSERTAR ---
    'insert' => [
        'tituloInsert' => "Ingreso de Nueva Clasificación CDD",
        'actionInsert' => "IngresarCdd2",
        'formFields' => [
            ['label' => 'Código', 'name' => 'Codigo', 'type' => 'number', 'required' => true],
            ['label' => 'Descripción', 'name' => 'Descripcion', 'type' => 'text', 'required' => true]
        ],
    ],

    // --- CONFIGURACIÓN ACTUALIZAR ---
    'update' => [
        'tituloUpdate' => "Actualización de Clasificación CDD",
        'actionUpdate' => "UpdateCdd2",
        'formFields' => [
            ['label' => 'Código', 'name' => 'Codigo', 'type' => 'number', 'required' => true, 'readonly' => true],
            ['label' => 'Descripción', 'name' => 'Descripcion', 'type' => 'text', 'required' => true]
        ],
    ],
];
