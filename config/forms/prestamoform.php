<?php
return [
    'global' => [
        'titulo' => "Listado de Préstamos",
        'headers' => ['Nº Control', 'Fecha Préstamo', 'Año', 'Devolución', 'Tipo'],
        'keys' => ['N_de_control', 'Fecha_de_prestamo', 'Año_del_prestamo', 'Devolucion_de_prestamo', 'Tipo_de_prestamo'],
        'controller' => "Prestamo",
        'actionUpdate' => "UpdatePrestamo",
        'actionDelete' => "DeletePrestamo",
        'idField' => "N_de_control"
    ],
    'insert' => [
        'tituloInsert' => "Ingreso de Nuevo Préstamo",
        'actionInsert' => "IngresarPrestamo",
        'formFields' => [
            ['label' => 'Nº Control', 'name' => 'N_de_control', 'type' => 'number', 'required' => true],
            ['label' => 'Fecha Préstamo', 'name' => 'Fecha_de_prestamo', 'type' => 'date', 'required' => true],
            ['label' => 'Año del Préstamo', 'name' => 'Año_del_prestamo', 'type' => 'number', 'required' => true],
            ['label' => 'Fecha Devolución', 'name' => 'Devolucion_de_prestamo', 'type' => 'date', 'required' => true],
            // Asumimos texto o select para Tipo? SQL dice varchar. Usare text para ser genérico.
            ['label' => 'Tipo de Préstamo', 'name' => 'Tipo_de_prestamo', 'type' => 'text', 'required' => true],
        ]
    ],
    'update' => [
        'tituloUpdate' => "Actualización de Préstamo",
        'actionUpdate' => "UpdatePrestamo2",
        'formFields' => [
            ['label' => 'Nº Control', 'name' => 'N_de_control', 'type' => 'number', 'required' => true, 'readonly' => true],
            ['label' => 'Fecha Préstamo', 'name' => 'Fecha_de_prestamo', 'type' => 'date', 'required' => true],
            ['label' => 'Año del Préstamo', 'name' => 'Año_del_prestamo', 'type' => 'number', 'required' => true],
            ['label' => 'Fecha Devolución', 'name' => 'Devolucion_de_prestamo', 'type' => 'date', 'required' => true],
            ['label' => 'Tipo de Préstamo', 'name' => 'Tipo_de_prestamo', 'type' => 'text', 'required' => true],
        ]
    ]
];
