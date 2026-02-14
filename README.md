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
