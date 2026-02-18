<?php
return [
    'global' => [
        'titulo' => "Listado de Soportes",
        'headers' => ['ID', 'Descripción', 'Prestable'],
        'keys' => ['ID', 'Descripcion', 'Prestable'],
        'controller' => "TSoporte",
        'actionUpdate' => "", // Read-only as per instructions (only Listar implemented in Controller)
        'actionDelete' => "",
        'idField' => "ID"
    ],
    'insert' => [
        'tituloInsert' => "Nuevo Soporte",
        'actionInsert' => "IngresarTSoporte",
        'formFields' => [
            ['label' => 'ID', 'name' => 'ID', 'type' => 'number', 'required' => true],
            ['label' => 'Descripción', 'name' => 'Descripcion', 'type' => 'text', 'required' => true],
            ['label' => 'Prestable', 'name' => 'Prestable', 'type' => 'number', 'required' => true]
        ]
    ],
    'update' => ['formFields' => []]
];
