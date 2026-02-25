<?php
return [
    // --- CONFIGURACIÓN GLOBAL (Lista) ---
    'global' => [
        'titulo' => "Listado de Préstamos",
        'headers' => ['Nº Control', 'F. Préstamo', 'F. Devolución', 'Ejemplar', 'Prestatario', 'Prestamista', 'Tipo'],
        'keys' => ['N_de_control', 'F_prestamo', 'F_devolucion', 'Titulo_Obra', 'Nombre_Prestatario', 'Username_Prestamista', 'T_prestamo'],
        'controller' => "Prestamo",
        'actionUpdate' => "UpdatePrestamo",
        'actionDelete' => "DeletePrestamo",
        'idField' => "N_de_control",
        'labelUpdate' => 'Renovar',
        'labelDelete' => 'Cancelar'
    ],

    // --- CONFIGURACIÓN INSERTAR ---
    'insert' => [
        'tituloInsert' => "Registro de Nuevo Préstamo",
        'actionInsert' => "IngresarPrestamo2",
        'formFields' => [
            // Fecha Préstamo
            ['label' => 'Fecha de Préstamo', 'name' => 'F_prestamo', 'type' => 'date', 'required' => true],
            // Fecha Devolución
            ['label' => 'Fecha de Devolución', 'name' => 'F_devolucion', 'type' => 'date', 'required' => true],

            // --- EJEMPLAR (datalist + 4 hidden) ---
            ['label' => 'Ejemplar', 'name' => 'Ejemplar_Display', 'type' => 'datalist', 'required' => true,
             'data-hidden' => 'N_de_Ejemplar,Año_Obras,Cutter_Autores,Codigo_CDD', 'options' => []],
            ['label' => 'N Ejemplar', 'name' => 'N_de_Ejemplar', 'type' => 'hidden', 'required' => true],
            ['label' => 'Año Obras', 'name' => 'Año_Obras', 'type' => 'hidden', 'required' => true],
            ['label' => 'Cutter Autores', 'name' => 'Cutter_Autores', 'type' => 'hidden', 'required' => true],
            ['label' => 'Código CDD', 'name' => 'Codigo_CDD', 'type' => 'hidden', 'required' => true],

            // --- PRESTATARIO (datalist + 1 hidden) ---
            ['label' => 'Prestatario (Lector)', 'name' => 'Prestatario_Display', 'type' => 'datalist', 'required' => true,
             'data-hidden' => 'DNI_Prestatario', 'options' => []],
            ['label' => 'DNI Prestatario', 'name' => 'DNI_Prestatario', 'type' => 'hidden', 'required' => true],

            // --- PRESTAMISTA (readonly, preset from session) ---
            ['label' => 'Prestamista (Admin)', 'name' => 'Username_Prestamista', 'type' => 'text', 'required' => true, 'readonly' => true],

            // --- TIPO DE PRESTAMO (select) ---
            ['label' => 'Tipo de Préstamo', 'name' => 'T_prestamo', 'type' => 'select', 'required' => true,
             'options' => ['En sala' => 'En sala', 'Circulante' => 'Circulante']],
        ]
    ]
];
