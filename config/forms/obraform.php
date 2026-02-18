<?php
return [
    // --- CONFIGURACIÓN GLOBAL (Lista) ---
    // Nota: La lista muestra campos con JOINs (nombres de Autor, Editorial, etc.)
    'global' => [
        'titulo' => "Listado de Obras",
        'headers' => ['Año', 'Autor', 'CDD', 'Título', 'Edición', 'Otros Autores', 'ISBN', 'ISSN', 'Descripción', 'L. Publicación', 'Dep. Legal', 'Editorial', 'Tipo de Obra'],
        'keys' => ['Año', 'Nombre_Autor', 'Desc_CDD', 'Titulo', 'Edicion', 'Otros_Autores', 'ISBN', 'ISNN', 'Descripcion', 'L_Publicacion', 'N_Dep_Legal', 'Nombre_Editorial', 'Desc_TObras'],
        'controller' => "Obra",
        'actionUpdate' => "UpdateObra",
        'actionDelete' => "DeleteObra",
        // Para la clave compuesta, usamos Año como idField y pasaremos los demás componentes por query string
        'idField' => "Año",
        // Clave compuesta: campos adicionales que se añaden a las URLs de Modificar/Eliminar
        'compositeKeys' => ['cutter' => 'Cutter_Autor', 'cdd' => 'Codigo_CDD']
    ],

    // --- CONFIGURACIÓN INSERTAR ---
    'insert' => [
        'tituloInsert' => "Ingreso de Nueva Obra",
        'actionInsert' => "IngresarObra2",
        'formFields' => [
            // Campos de clave compuesta
            ['label' => 'Año', 'name' => 'Anio', 'type' => 'number', 'required' => true],
            
            // Campo con datalist: Autor
            // type='datalist' renderiza un input con <datalist>, 'options' se inyectan desde el controlador
            // 'data-hidden' indica el campo hidden que almacena el ID real
            ['label' => 'Autor (Nombre o Cutter)', 'name' => 'Cutter_Autor_display', 'type' => 'datalist', 'required' => true,
             'data-hidden' => 'Cutter_Autor', 'options' => []],
            ['label' => 'Cutter Autor', 'name' => 'Cutter_Autor', 'type' => 'hidden', 'required' => true],

            // Campo con datalist: CDD
            ['label' => 'Código CDD', 'name' => 'Codigo_CDD_display', 'type' => 'datalist', 'required' => true,
             'data-hidden' => 'Codigo_CDD', 'options' => []],
            ['label' => 'Codigo CDD', 'name' => 'Codigo_CDD', 'type' => 'hidden', 'required' => true],

            // Campos nativos
            ['label' => 'Título', 'name' => 'Titulo', 'type' => 'text', 'required' => true],
            ['label' => 'Edición', 'name' => 'Edicion', 'type' => 'text', 'required' => true],
            ['label' => 'Otros Autores (Cutters separados por coma)', 'name' => 'Otros_Autores', 'type' => 'text', 'required' => true],
            ['label' => 'ISBN', 'name' => 'ISBN', 'type' => 'text', 'required' => true],
            ['label' => 'ISSN', 'name' => 'ISNN', 'type' => 'text', 'required' => true],
            ['label' => 'Descripción', 'name' => 'Descripcion', 'type' => 'textarea', 'required' => true],
            ['label' => 'Lugar de Publicación', 'name' => 'L_Publicacion', 'type' => 'text', 'required' => true],
            ['label' => 'Nº de Depósito Legal', 'name' => 'N_Dep_Legal', 'type' => 'text', 'required' => true],

            // Campo con datalist: Editorial
            ['label' => 'Editorial (Nombre o ID)', 'name' => 'ID_Editorial_display', 'type' => 'datalist', 'required' => true,
             'data-hidden' => 'ID_Editorial', 'options' => []],
            ['label' => 'ID Editorial', 'name' => 'ID_Editorial', 'type' => 'hidden', 'required' => true],

            // Campo con datalist: Tipo de Obras
            ['label' => 'Tipo de Obra (Descripción)', 'name' => 'ID_T_Obras_display', 'type' => 'datalist', 'required' => true,
             'data-hidden' => 'ID_T_Obras', 'options' => []],
            ['label' => 'ID Tipo de Obra', 'name' => 'ID_T_Obras', 'type' => 'hidden', 'required' => true],
        ],
    ],

    // --- CONFIGURACIÓN ACTUALIZAR ---
    'update' => [
        'tituloUpdate' => "Actualización de Obra",
        'actionUpdate' => "UpdateObra2",
        'formFields' => [
            // Campos de clave compuesta (readonly en update)
            ['label' => 'Año', 'name' => 'Anio', 'type' => 'number', 'required' => true, 'readonly' => true],
            
            // Autor - readonly (parte de la PK)
            ['label' => 'Autor (Nombre o Cutter)', 'name' => 'Cutter_Autor_display', 'type' => 'datalist', 'required' => true, 'readonly' => true,
             'data-hidden' => 'Cutter_Autor', 'options' => []],
            ['label' => 'Cutter Autor', 'name' => 'Cutter_Autor', 'type' => 'hidden', 'required' => true],

            // CDD - readonly (parte de la PK)
            ['label' => 'Código CDD', 'name' => 'Codigo_CDD_display', 'type' => 'datalist', 'required' => true, 'readonly' => true,
             'data-hidden' => 'Codigo_CDD', 'options' => []],
            ['label' => 'Codigo CDD', 'name' => 'Codigo_CDD', 'type' => 'hidden', 'required' => true],

            // Campos nativos editables
            ['label' => 'Título', 'name' => 'Titulo', 'type' => 'text', 'required' => true],
            ['label' => 'Edición', 'name' => 'Edicion', 'type' => 'text', 'required' => true],
            ['label' => 'Otros Autores (Cutters separados por coma)', 'name' => 'Otros_Autores', 'type' => 'text', 'required' => false],
            ['label' => 'ISBN', 'name' => 'ISBN', 'type' => 'text', 'required' => true],
            ['label' => 'ISSN', 'name' => 'ISNN', 'type' => 'text', 'required' => true],
            ['label' => 'Descripción', 'name' => 'Descripcion', 'type' => 'textarea', 'required' => true],
            ['label' => 'Lugar de Publicación', 'name' => 'L_Publicacion', 'type' => 'text', 'required' => true],
            ['label' => 'Nº de Depósito Legal', 'name' => 'N_Dep_Legal', 'type' => 'text', 'required' => true],

            // Editorial - editable
            ['label' => 'Editorial (Nombre o ID)', 'name' => 'ID_Editorial_display', 'type' => 'datalist', 'required' => true,
             'data-hidden' => 'ID_Editorial', 'options' => []],
            ['label' => 'ID Editorial', 'name' => 'ID_Editorial', 'type' => 'hidden', 'required' => true],

            // Tipo de Obras - editable
            ['label' => 'Tipo de Obra (Descripción)', 'name' => 'ID_T_Obras_display', 'type' => 'datalist', 'required' => true,
             'data-hidden' => 'ID_T_Obras', 'options' => []],
            ['label' => 'ID Tipo de Obra', 'name' => 'ID_T_Obras', 'type' => 'hidden', 'required' => true],
        ],
    ],
];
