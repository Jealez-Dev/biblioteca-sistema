<?php
return [
    // --- CONFIGURACIÓN GLOBAL (Lista) ---
    'global' => [
        'titulo' => "Listado de Cancelaciones",
        'headers' => ['ID', 'Admin', 'F. Cancelación', 'Préstamo (Metadata)'],
        'keys' => ['ID', 'Username', 'F_cancelacion', 'Prestamo'],
        'controller' => "Cancelacion",
        'actionUpdate' => "UpdateCancelacion",
        'actionDelete' => "DeleteCancelacion",
        'idField' => "ID"
    ],

    // --- CONFIGURACIÓN INSERTAR ---
    'insert' => [
        'tituloInsert' => "Registro de Cancelación",
        'actionInsert' => "IngresarCancelacion2",
        'formFields' => [
            // Prestamo (datalist + hidden)
            ['label' => 'Préstamo a Cancelar', 'name' => 'Prestamo_Display', 'type' => 'datalist', 'required' => true,
             'data-hidden' => 'N_control_prestamo', 'options' => []],
            ['label' => 'N Control Préstamo', 'name' => 'N_control_prestamo', 'type' => 'hidden', 'required' => true],

            // Admin (readonly preset)
            ['label' => 'Admin', 'name' => 'Username', 'type' => 'text', 'required' => true, 'readonly' => true],

            // Fecha cancelación
            ['label' => 'F. Cancelación', 'name' => 'F_cancelacion', 'type' => 'date', 'required' => true],
        ]
    ],

    // --- CONFIGURACIÓN ACTUALIZAR ---
    'update' => [
        'tituloUpdate' => "Actualización de Cancelación",
        'actionUpdate' => "UpdateCancelacion2",
        'formFields' => [
            ['label' => 'ID', 'name' => 'ID', 'type' => 'number', 'required' => true, 'readonly' => true],
            ['label' => 'Préstamo (Metadata)', 'name' => 'Prestamo', 'type' => 'text', 'required' => true],
            ['label' => 'Admin', 'name' => 'Username', 'type' => 'text', 'required' => true],
            ['label' => 'F. Cancelación', 'name' => 'F_cancelacion', 'type' => 'date', 'required' => true],
        ]
    ]
];
