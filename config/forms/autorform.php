<?php
return [
    // --- CONFIGURACIÓN GLOBAL (Lista) ---
    'global' => [
        'titulo' => "Listado de Autores",
        'headers' => ['Cutter', 'Nombre', 'Nacionalidad'],
        'keys' => ['Cutter', 'Nombre', 'Nacionalidad'],
        'controller' => "Autor",
        'actionUpdate' => "UpdateAutor",
        'actionDelete' => "DeleteAutor",
        'idField' => "Cutter"
    ],

    // --- CONFIGURACIÓN INSERTAR ---
    'insert' => [
        'tituloInsert' => "Ingreso de Nuevo Autor",
        'actionInsert' => "IngresarAutor2",
        'formFields' => [
            ['label' => 'Seleccione el Cutter', 'name' => 'Cutter', 'type' => 'text', 'required' => true],
            ['label' => 'Nombre', 'name' => 'Nombre', 'type' => 'text', 'required' => true],
            ['label' => 'Nacionalidad', 'name' => 'Nacionalidad', 'type' => 'text', 'required' => true]
        ],
    ],

    // --- CONFIGURACIÓN ACTUALIZAR ---
    'update' => [
        'tituloUpdate' => "Actualización de Autor",
        'actionUpdate' => "UpdateAutor2",
        'formFields' => [
            ['label' => 'Cutter', 'name' => 'Cutter', 'type' => 'text', 'required' => true, 'readonly' => true],
            ['label' => 'Nombre', 'name' => 'Nombre', 'type' => 'text', 'required' => true],
            ['label' => 'Nacionalidad', 'name' => 'Nacionalidad', 'type' => 'text', 'required' => true]
        ],
    ],
];

