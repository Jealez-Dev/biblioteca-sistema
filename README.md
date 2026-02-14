# biblioteca-Sonia-Quijada

## Instalación y Configuración (Paso a Paso)

Esta guía asume que no tienes conocimientos de Git y usas Windows con XAMPP o WAMP.

### 1. Descargar el Proyecto
1. Ve al botón verde "Code" en la parte superior derecha de esta página.
2. Selecciona la opción **"Download ZIP"**.
3. Extrae el archivo descargado. Deberías obtener una carpeta llamada `biblioteca-sistema-main` o similar.
4. Cambia el nombre de la carpeta a `biblioteca-sistema`.

### 2. Mover los Archivos
*   **Si usas XAMPP:** Mueve la carpeta `biblioteca-sistema` a `C:/xampp/htdocs/`.
*   **Si usas WAMP:** Mueve la carpeta `biblioteca-sistema` a `C:/wamp/www/`.

### 3. Configurar la Base de Datos
1. Abre XAMPP o WAMP y asegúrate de iniciar los servicios **Apache** y **MySQL**.
2. Ve a tu navegador y entra a: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
3. Crea una nueva base de datos llamada **`noticias`**.
4. Selecciona la base de datos recién creada y ve a la pestaña **"Importar"**.
5. Selecciona el archivo `noticias.sql` que se encuentra dentro de la carpeta del proyecto (`biblioteca-sistema`).
6. Haz clic en **"Continuar"** para importar las tablas.

### 4. Configurar la Conexión
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

### 5. Ejecutar el Sistema
1. Abre tu navegador web.
2. Ingresa a la siguiente dirección: [http://localhost/biblioteca-sistema](http://localhost/biblioteca-sistema)
