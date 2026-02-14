<?php
return [
    // --- CONFIGURACIÓN GLOBAL (Lista) ---
    'global' => [
        'titulo' => "Listado de Administradores",
        'headers' => ['DNI', 'Username', 'Password'],
        'keys' => ['DNI_Usuario', 'Username', 'Password'],
        'controller' => "Admin",
        'actionUpdate' => "UpdateAdmin",
        'actionDelete' => "DeleteAdmin",
        'idField' => "DNI_Usuario"
    ],

    // --- CONFIGURACIÓN INSERTAR ---
    'insert' => [
        'tituloInsert' => "Ingreso de Nuevo Admin",
        'actionInsert' => "IngresarAdmin2",
        'formFields' => [
            ['label' => 'Seleccione el DNI', 'name' => 'DNI', 'type' => 'select', 'required' => true],
            ['label' => 'Username', 'name' => 'Username', 'type' => 'text', 'required' => true],
            ['label' => 'Password', 'name' => 'Password', 'type' => 'text', 'required' => true]
        ],
    ],

    // --- CONFIGURACIÓN ACTUALIZAR ---
    'update' => [
        'tituloUpdate' => "Actualización de Admin",
        'actionUpdate' => "UpdateAdmin2",
        'formFields' => [
            ['label' => 'DNI', 'name' => 'DNI', 'type' => 'number', 'required' => true, 'readonly' => true],
            ['label' => 'Username', 'name' => 'Username', 'type' => 'text', 'required' => true],
            ['label' => 'Password', 'name' => 'Password', 'type' => 'text', 'required' => true]
        ],
    ],
];
