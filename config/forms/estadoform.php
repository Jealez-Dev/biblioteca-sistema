<?php
return [
    'global' => [
        'titulo' => "Listado de Estados",
        'headers' => ['ID', 'Descripción'],
        'keys' => ['ID', 'Descripcion'],
        'controller' => "Estado",
        'actionUpdate' => "",
        'actionDelete' => "",
        'idField' => "ID"
    ],
    'insert' => [
        'tituloInsert' => "Nuevo Estado",
        'actionInsert' => "IngresarEstado",
        'formFields' => [
            ['label' => 'ID', 'name' => 'ID', 'type' => 'number', 'required' => true],
            ['label' => 'Descripción', 'name' => 'Descripcion', 'type' => 'text', 'required' => true]
        ]
    ],
    'update' => ['formFields' => []]
];
