# biblioteca-Sonia-Quijada

## Instalación y Configuración (Paso a Paso)

Esta guía asume que no tienes conocimientos de Git y usas Windows con XAMPP o WAMP.

### 1. Prerrequisitos
1.  Tener instalado **XAMPP** o **WAMP**.
2.  Tener instalado **Git**. Si no lo tienes, descárgalo e instálalo desde [git-scm.com](https://git-scm.com/).

### 2. Clonar el Repositorio
Para trabajar en el proyecto, es obligatorio usar Git. Sigue estos pasos:

1.  Abre la terminal de tu computadora (Git Bash, PowerShell o CMD).
2.  Navega a la carpeta pública de tu servidor local:
    *   **En XAMPP:**
        ```bash
        cd C:/xampp/htdocs
        ```
    *   **En WAMP:**
        ```bash
        cd C:/wamp/www
        ```
3.  Clona el repositorio ejecutando el siguiente comando:
    ```bash
    git clone https://github.com/Jealez-Dev/biblioteca-sistema.git
    ```
4.  Entra a la carpeta del proyecto:
    ```bash
    cd biblioteca-sistema
    ```

### 3. Crear tu Rama de Trabajo
Para no afectar la versión principal del código, crea tu propia rama con tu nombre:

1.  Crea y muévete a una nueva rama (reemplaza `tu-nombre` por tu primer nombre, ej: `juan-cambios`):
    ```bash
    git checkout -b tu-nombre-cambios
    ```
2.  Ahora puedes hacer cambios en esta rama sin afectar a los demás.


### 4. Configurar la Base de Datos
1. Abre XAMPP o WAMP y asegúrate de iniciar los servicios **Apache** y **MySQL**.
2. Ve a tu navegador y entra a: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
3. Crea una nueva base de datos llamada **`noticias`**.
4. Selecciona la base de datos recién creada y ve a la pestaña **"Importar"**.
5. Selecciona el archivo `noticias.sql` que se encuentra dentro de la carpeta del proyecto (`biblioteca-sistema`).
6. Haz clic en **"Continuar"** para importar las tablas.

### 5. Configurar la Conexión
1. Ve a la carpeta del proyecto y abre el archivo `config/database.php` con un editor de texto (Bloc de notas, VS Code, etc.).
2. Verifica que los datos coincidan con tu configuración de XAMPP/WAMP:
    ```php
    return array(
        "driver"    =>"mysql",
        "host"      =>"localhost",
        "user"      =>"root",
        "pass"      =>"",      // En XAMPP la contraseña suele estar vacía
        "database"  =>"noticias",
        "charset"   =>"utf8"
    );
    ```
    *Nota: Si tu MySQL tiene contraseña, colócala en el campo "pass".*

### 6. Ejecutar el Sistema
1. Abre tu navegador web.
2. Ingresa a la siguiente dirección: [http://localhost/biblioteca-sistema](http://localhost/biblioteca-sistema)


### 7. Cómo usar las Vistas Genéricas

Este sistema utiliza un patrón de diseño basado en configuración para facilitar la creación de módulos CRUD (Crear, Leer, Actualizar, Eliminar).

**Pasos para integrar un nuevo módulo:**

1.  **Crear el Modelo (`models/TuModelo.php`)**:
    Debe implementar métodos para consultar, insertar y actualizar datos.
    *   `ListarTuModelo()`
    *   `BuscarTuModeloById($id)`
    *   `IngresarTuModelo2(...)`
    *   `UpdateTuModelo2(...)`
    *   `DeleteTuModelo(...)`

2.  **Crear la Configuración (`config/forms/tuform.php`)**:
    Este archivo define qué campos se mostrarán en la tabla y en los formularios.
    
    **Ejemplo completo:**
    ```php
    return [
        // --- CONFIGURACIÓN PARA LA LISTA (list.php) ---
        'global' => [
            'titulo' => "Listado de Productos",       // Título de la página
            'headers' => ['ID', 'Nombre', 'Precio'],  // Encabezados de la tabla
            'keys' => ['id_prod', 'nombre', 'precio'],// Nombres de las columnas en la BD
            'controller' => "Producto",               // Nombre del Controlador (para rutas)
            'actionUpdate' => "UpdateProducto",       // Método del controlador para boton Editar
            'actionDelete' => "DeleteProducto",       // Método del controlador para boton Eliminar
            'idField' => "id_prod"                    // Nombre del campo clave primaria
        ],
    
        // --- CONFIGURACIÓN PARA INSERTAR (insert.php) ---
        'insert' => [
            'tituloInsert' => "Ingreso de Producto",
            'actionInsert' => "IngresarProducto2",    // Método que procesa el POST
            'formFields' => [
                // Array de campos:
                ['label' => 'Nombre', 'name' => 'nombre', 'type' => 'text', 'required' => true],
                ['label' => 'Precio', 'name' => 'precio', 'type' => 'number', 'step' => '0.01', 'required' => true],
                ['label' => 'Categoría', 'name' => 'cat_id', 'type' => 'select', 'options' => [], 'required' => true]
            ],
        ],
    
        // --- CONFIGURACIÓN PARA ACTUALIZAR (update.php) ---
        'update' => [
            'tituloUpdate' => "Editar Producto",
            'actionUpdate' => "UpdateProducto2",      // Método que procesa el POST de actualización
            'formFields' => [
                // El campo ID suele ser readonly en update
                ['label' => 'ID', 'name' => 'id_prod', 'type' => 'number', 'readonly' => true],
                ['label' => 'Nombre', 'name' => 'nombre', 'type' => 'text', 'required' => true],
                ['label' => 'Precio', 'name' => 'precio', 'type' => 'number', 'step' => '0.01', 'required' => true],
                 // Nota: Las 'options' de los select se suelen llenar en el controlador
                ['label' => 'Categoría', 'name' => 'cat_id', 'type' => 'select', 'options' => [], 'required' => true]
            ],
        ]
    ];
    ```

3.  **Crear el Controlador (`controllers/TuController.php`)**:
    Es el cerebro que conecta todo. Sus funciones principales son:
    *   **Constructor:** Carga los modelos necesarios.
    *   **Métodos de Vista (`Listar`, `Ingresar`, `Update`):**
        1.  Obtienen datos del modelo (si es necesario).
        2.  Cargan la configuración del formulario (`require .../tuform.php`).
        3.  Usan `extract()` para convertir el array de configuración en variables individuales (`$titulo`, `$headers`, `$formFields`...) que las vistas genéricas esperan recibir.
        4.  Invocan a la vista genérica correspondiente (`views/generic/list.php`, `insert.php` o `update.php`).
    *   **Métodos de Acción (`Ingresar...2`, `Update...2`):**
        1.  Reciben los datos del formulario vía `$_POST`.
        2.  Llaman al método del modelo para guardar en la BD.
        3.  Redirigen o muestran un mensaje de éxito/error.

4.  **Registrar la Ruta (`views/admon/routing.php`)**:
    Para que el sistema sepa que tu controlador existe, debes registrarlo en dos lugares dentro de este archivo:
    *   **Array `$controllers`**: Agrega el nombre de tu controlador (clave) y la lista de métodos permitidos (valor). Esto funciona como una lista blanca de seguridad.
    *   **Switch `$controller`**: Agrega un `case` para instanciar tu controlador cuando sea llamado.

5.  **Agregar al Menú (`views/admon/submenu.php`)**:
    Finalmente, para que el usuario pueda acceder, agrega un nuevo ítem en el menú de navegación (navbar) apuntando a la acción de listar de tu controlador:
    `<a href="?controller=TuControlador&action=ListarTuModelo">...</a>`


