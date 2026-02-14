<?php
return [
    // --- CONFIGURACIÓN GLOBAL (Lista) ---
    'global' => [
        'titulo' => "Listado de Usuarios", // Titulo de la lista
        'headers' => ['DNI', 'Nombre', 'Apellido', 'Edad', 'Correo', 'Teléfono'], // Cabeceras de la tabla
        'keys' => ['DNI', 'Nombre', 'Apellido', 'Edad', 'Correo', 'Num_Telefono'], // Claves de la tabla
        'controller' => "User", // Controlador
        'actionUpdate' => "UpdateUser", // Accion para actualizar
        'actionDelete' => "DeleteUser", // Accion para eliminar
        'idField' => "DNI",
    ],

    // --- CONFIGURACIÓN INSERTAR ---
    'insert' => [
        'tituloInsert' => "Ingreso de Nuevo Usuario", // Titulo del formulario 
        'actionInsert' => "IngresarUser2", // Accion para insertar
        'formFields' => [ // Campos del formulario (Se puede modificar todo, adaptar a las necesidades de las diferentes tablas)
            ['label' => 'DNI', 'name' => 'DNI', 'type' => 'number', 'required' => true], // Campo DNI
            ['label' => 'Nombre', 'name' => 'Nombre', 'type' => 'text', 'required' => true], // Campo Nombre
            ['label' => 'Apellido', 'name' => 'Apellido', 'type' => 'text', 'required' => true], // Campo Apellido
            ['label' => 'Edad', 'name' => 'Edad', 'type' => 'number', 'required' => true], // Campo Edad
            ['label' => 'Correo', 'name' => 'Correo', 'type' => 'email', 'required' => true], // Campo Correo
            ['label' => 'Teléfono', 'name' => 'Num_Telefono', 'type' => 'number', 'required' => true], // Campo Teléfono
        ],
    ],

    // --- CONFIGURACIÓN ACTUALIZAR ---
    'update' => [
        'tituloUpdate' => "Actualización de Usuario", // Titulo del formulario 
        'actionUpdate' => "UpdateUser2", // Accion para actualizar
        'formFields' => [ // Campos del formulario (Se puede modificar todo, adaptar a las necesidades de las diferentes tablas)
            ['label' => 'DNI', 'name' => 'DNI', 'type' => 'number', 'required' => true, 'readonly' => true], // Campo DNI
            ['label' => 'Nombre', 'name' => 'Nombre', 'type' => 'text', 'required' => true], // Campo Nombre
            ['label' => 'Apellido', 'name' => 'Apellido', 'type' => 'text', 'required' => true], // Campo Apellido
            ['label' => 'Edad', 'name' => 'Edad', 'type' => 'number', 'required' => true], // Campo Edad
            ['label' => 'Correo', 'name' => 'Correo', 'type' => 'email', 'required' => true], // Campo Correo
            ['label' => 'Teléfono', 'name' => 'Num_Telefono', 'type' => 'number', 'required' => true], // Campo Teléfono
        ],
    ],
];
