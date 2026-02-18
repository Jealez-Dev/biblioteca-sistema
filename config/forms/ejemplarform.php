<?php
return [
    'global' => [
        'titulo' => "Listado de Ejemplares",
        // Note: Headers correspond to list.php display
        'headers' => ['Nº Ejemplar', 'Obra (Título)', 'Año', 'Estado', 'Soporte'],
        // Keys match the column aliases in EjemplarModel::ListarEjemplar
        'keys' => ['N_de_Ejemplar', 'Titulo_Obra', 'Año_Obras', 'Desc_Estado', 'Desc_Soporte'],
        'controller' => "Ejemplar",
        'actionUpdate' => "UpdateEjemplar",
        'actionDelete' => "DeleteEjemplar",
        'idField' => "N_de_Ejemplar",
        // Composite keys for Update/Delete URLs
        'compositeKeys' => [
            'anio' => 'Año_Obras',
            'cutter' => 'Cutter_Autores',
            'cdd' => 'Codigo_CDD'
        ]
    ],
    'insert' => [
        'tituloInsert' => "Ingreso de Nuevo Ejemplar",
        'actionInsert' => "IngresarEjemplar2",
        'formFields' => [
            ['label' => 'Número de Ejemplar', 'name' => 'N_de_Ejemplar', 'type' => 'number', 'required' => true],
            
            // --- FIELD: OBRA (Composite) ---
            // Display field (datalist)
            // data-hidden: comma-separated names of hidden fields to populate
            ['label' => 'Obra Associada', 'name' => 'Obra_Display', 'type' => 'datalist', 'required' => true,
             'data-hidden' => 'Año_Obras,Cutter_Autores,Codigo_CDD', 'options' => []],
             
            // Hidden fields for Obra PK
            ['label' => 'Año Obra', 'name' => 'Año_Obras', 'type' => 'hidden', 'required' => true],
            ['label' => 'Cutter Autor', 'name' => 'Cutter_Autores', 'type' => 'hidden', 'required' => true],
            ['label' => 'Código CDD', 'name' => 'Codigo_CDD', 'type' => 'hidden', 'required' => true],

            // --- FIELD: ESTADO ---
            ['label' => 'Estado', 'name' => 'Estado_Display', 'type' => 'datalist', 'required' => true,
             'data-hidden' => 'ID_Estado', 'options' => []],
            ['label' => 'ID Estado', 'name' => 'ID_Estado', 'type' => 'hidden', 'required' => true],

            // --- FIELD: T_SOPORTE ---
            ['label' => 'Soporte', 'name' => 'Soporte_Display', 'type' => 'datalist', 'required' => true,
             'data-hidden' => 'ID_Soporte', 'options' => []],
            ['label' => 'ID Soporte', 'name' => 'ID_Soporte', 'type' => 'hidden', 'required' => true],
        ]
    ],
    'update' => [
        'tituloUpdate' => "Actualización de Ejemplar",
        'actionUpdate' => "UpdateEjemplar2", // Distinct action usually
        'formFields' => [
            // PK fields are readonly in update
            ['label' => 'Número de Ejemplar', 'name' => 'N_de_Ejemplar', 'type' => 'number', 'required' => true, 'readonly' => true],
            
            // Obra is part of PK, thus readonly
            ['label' => 'Obra Associada', 'name' => 'Obra_Display', 'type' => 'datalist', 'required' => true, 'readonly' => true,
             'data-hidden' => 'Año_Obras,Cutter_Autores,Codigo_CDD', 'options' => []],
            
            ['label' => 'Año Obra', 'name' => 'Año_Obras', 'type' => 'hidden', 'required' => true],
            ['label' => 'Cutter Autor', 'name' => 'Cutter_Autores', 'type' => 'hidden', 'required' => true],
            ['label' => 'Código CDD', 'name' => 'Codigo_CDD', 'type' => 'hidden', 'required' => true],

            // Editable fields
            ['label' => 'Estado', 'name' => 'Estado_Display', 'type' => 'datalist', 'required' => true,
             'data-hidden' => 'ID_Estado', 'options' => []],
            ['label' => 'ID Estado', 'name' => 'ID_Estado', 'type' => 'hidden', 'required' => true],

            ['label' => 'Soporte', 'name' => 'Soporte_Display', 'type' => 'datalist', 'required' => true,
             'data-hidden' => 'ID_Soporte', 'options' => []],
            ['label' => 'ID Soporte', 'name' => 'ID_Soporte', 'type' => 'hidden', 'required' => true],
        ]
    ]
];
