<?php
return [
    // --- CONFIGURACIÓN GLOBAL (Lista) ---
    'global' => [
        'titulo' => "Listado de Renovaciones",
        'headers' => ['ID', 'Admin', 'F. Renovación', 'Préstamo (Metadata)'],
        'keys' => ['ID', 'Username', 'F_renovacion', 'Prestamo'],
        'controller' => "Renovacion",
        'actionUpdate' => "UpdateRenovacion",
        'actionDelete' => "DeleteRenovacion",
        'idField' => "ID"
    ],

    // --- CONFIGURACIÓN INSERTAR ---
    'insert' => [
        'tituloInsert' => "Registro de Renovación",
        'actionInsert' => "IngresarRenovacion2",
        'formFields' => [
            // Prestamo (datalist + hidden)
            ['label' => 'Préstamo', 'name' => 'Prestamo_Display', 'type' => 'datalist', 'required' => true,
             'data-hidden' => 'N_control_prestamo', 'options' => []],
            ['label' => 'N Control Préstamo', 'name' => 'N_control_prestamo', 'type' => 'hidden', 'required' => true],

            // Nueva fecha de devolución
            ['label' => 'Nueva F. Devolución', 'name' => 'New_F_devolucion', 'type' => 'date', 'required' => true],

            // Admin (readonly preset)
            ['label' => 'Admin', 'name' => 'Username', 'type' => 'text', 'required' => true, 'readonly' => true],

            // Fecha renovación
            ['label' => 'F. Renovación', 'name' => 'F_renovacion', 'type' => 'date', 'required' => true],
        ]
    ],

    // --- CONFIGURACIÓN ACTUALIZAR ---
    'update' => [
        'tituloUpdate' => "Actualización de Renovación",
        'actionUpdate' => "UpdateRenovacion2",
        'formFields' => [
            ['label' => 'ID', 'name' => 'ID', 'type' => 'number', 'required' => true, 'readonly' => true],

            // Prestamo string field
            ['label' => 'Préstamo (Metadata)', 'name' => 'Prestamo', 'type' => 'text', 'required' => true],

            // Admin
            ['label' => 'Admin', 'name' => 'Username', 'type' => 'text', 'required' => true],

            // Fecha renovación
            ['label' => 'F. Renovación', 'name' => 'F_renovacion', 'type' => 'date', 'required' => true],
        ]
    ]
];
