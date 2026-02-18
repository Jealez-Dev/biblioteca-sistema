<?php
/*
 * Vista Genérica para Listados
 * 
 * Variables esperadas:
 * $titulo          : String - Título de la página
 * $headers         : Array  - Lista de encabezados de la tabla (ej: ['Id', 'Nombre', ...])
 * $registros       : Mysqli Result - Resultado de la consulta a la BD
 * $keys            : Array  - Nombres de las columnas en la BD a mostrar (ej: ['id', 'nombre', ...])
 * $idField         : String - Nombre del campo ID para las acciones (ej: 'id', 'DNI')
 * $controller      : String - Nombre del controlador para los enlaces (ej: 'Noticia', 'Usuario')
 * $actionUpdate    : String - Nombre de la acción para modificar (ej: 'UpdateNoticia')
 * $actionDelete    : String - Nombre de la acción para eliminar (ej: 'DeleteNoticia')
 * 
 * Opcional:
 * $extraActions    : Array  - Acciones extra si se necesitan
 */

$numrows = mysqli_num_rows($registros);
?>

<div class="container">
    <br> <br>
    <h4> <?php echo $titulo; ?> </h4>
    <br> <br>

    <?php if (isset($alert)): ?>
                    <div class="alert <?php echo $alert['type']; ?> alert-dismissible fade show" role="alert">
                        <?php echo $alert['message']; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

    <div class="table-responsive">
        <table id="dtBasicExample" data-order='[[ 0, "asc" ]]' data-page-length='10' class="table table-sm table-striped table-hover table-bordered" cellspacing="0" width="100%">
            
            <thead>
                <tr>
                    <?php foreach ($headers as $header): ?>
                        <th class="th-sm"><?php echo $header; ?></th>
                    <?php endforeach; ?>
                    <!-- Columnas fijas para acciones -->
                    <th class="th-sm">Modificar</th>
                    <th class="th-sm">Eliminar</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                if ($numrows != 0) {
                    while ($row = mysqli_fetch_array($registros)) {
                        // Capturamos el ID para las acciones
                        $idValue = $row[$idField];
                        ?>
                        <tr>
                            <?php foreach ($keys as $key): ?>
                                <td>
                                    <?php 
                                    // Lógica especial para imágenes o enlaces si es necesario
                                    // Por ahora mostramos el texto plano, pero podrías agregar lógica
                                    // para detectar si es una URL de imagen, etc.
                                    if (strpos($key, 'url_imagen') !== false || strpos($key, 'img') !== false) {
                                        echo "<a href='" . $row[$key] . "' target='_blank'><img width='50px' height='50px' src='" . $row[$key] . "' alt=''></a>";
                                    } elseif (strpos($key, 'url') !== false) {
                                         echo "<a href='" . $row[$key] . "' target='_blank'>" . $row[$key] . "</a>";
                                    } else {
                                        echo $row[$key]; 
                                    }
                                    ?>
                                </td>
                            <?php endforeach; ?>

                            <?php 
                            // Construir parámetros extra para clave compuesta (si existe)
                            $extraParams = '';
                            if (isset($compositeKeys) && is_array($compositeKeys)) {
                                foreach ($compositeKeys as $paramName => $fieldName) {
                                    $extraParams .= '&' . $paramName . '=' . urlencode($row[$fieldName]);
                                }
                            }
                            ?>

                            <!-- Botón Modificar -->
                            <td align="center">
                                <a href="?controller=<?php echo $controller; ?>&action=<?php echo $actionUpdate; ?>&id=<?php echo $idValue; ?>&i=<?php echo $idValue; ?><?php echo $extraParams; ?>" title="Modificar">
                                    <img width="50px" height="50px" src="../biblioteca-sistema/imagenes/update_icon.jpg" alt="Modificar">
                                </a>
                            </td>

                            <!-- Botón Eliminar -->
                            <td align="center">
                                <a href="?controller=<?php echo $controller; ?>&action=<?php echo $actionDelete; ?>&id=<?php echo $idValue; ?>&i=<?php echo $idValue; ?><?php echo $extraParams; ?>" title="Eliminar">
                                    <img width="50px" height="50px" src="../biblioteca-sistema/imagenes/delete_icon.jpg" alt="Eliminar">
                                </a>
                            </td>
                        </tr>
                    <?php 
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
