<?php
/*
 * Vista Genérica para Inserción
 * 
 * Variables esperadas:
 * $tituloInsert    : String - Título de la sección de inserción (ej: 'Ingreso de Usuarios')
 * $actionInsert    : String - Acción del formulario (ej: 'IngresarUser1')
 * $controller      : String - Controlador (ej: 'User')
 * $formFields      : Array  - Configuración de campos
 *                    Estructura de cada campo:
 *                    [
 *                      'label' => 'Nombre',
 *                      'name' => 'nombre',
 *                      'type' => 'text'|'number'|'date'|'select'|'textarea'|'password'|'email',
 *                      'required' => true|false,
 *                      'readonly' => true|false,
 *                      'value' => 'default_value', // Opcional
 *                      'options' => ['Opción 1', 'Opción 2'], // Solo para select
 *                      'step' => '0.01', // Opcional para number/date
 *                      'pattern' => '[0-9]+', // Opcional para validación regex
 *                      'title' => 'Mensaje de validación', // Opcional
 *                    ]
 * 
 * Y las variables necesarias para la lista (ya que se incluirá abajo):
 * $titulo          : Título de la lista
 * $headers         : Array de encabezados
 * $registros       : Datos de la BD
 * $keys            : Columnas de la BD
 * $idField         : Campo ID
 * $actionUpdate    : Acción modificar
 * $actionDelete    : Acción eliminar
 */
?>

<div class="container">
    <div class="page-content">
        <form action="?controller=<?php echo $controller; ?>&action=<?php echo $actionInsert; ?>" method="POST">
            <div class="col-12">
                <br>
                <h4> <?php echo $tituloInsert; ?> </h4>
                <br>
                
                <br>
                
                <?php if (isset($alert)): ?>
                    <div class="alert <?php echo $alert['type']; ?> alert-dismissible fade show" role="alert">
                        <?php echo $alert['message']; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <div class="alert alert-secondary">
                    <div class="row">

                        <?php foreach ($formFields as $field): 
                            // Valores por defecto
                            $type = $field['type'] ?? 'text';
                            $required = isset($field['required']) && $field['required'] ? 'required' : '';
                            $readonly = isset($field['readonly']) && $field['readonly'] ? 'readonly' : '';
                            $value = $field['value'] ?? '';
                            $step = isset($field['step']) ? "step='{$field['step']}'" : '';
                            $pattern = isset($field['pattern']) ? "pattern='{$field['pattern']}'" : '';
                            $title = isset($field['title']) ? "title='{$field['title']}'" : '';
                            $options = $field['options'] ?? [];
                        ?>

                        <?php if ($type === 'hidden'): ?>
                            <!-- Campo oculto: se renderiza sin contenedor visual -->
                            <input type="hidden" 
                                   name="<?php echo $field['name']; ?>" 
                                   value="<?php echo $value; ?>">

                        <?php else: ?>
                        <div class="col-md-6 mb-3" id="div_<?php echo $field['name']; ?>"> <!-- Usamos col-md-6 para 2 columnas -->
                            <label for="<?php echo $field['name']; ?>" class="font-weight-bold"> 
                                <?php echo $field['label']; ?>: 
                            </label>

                            <?php if ($type === 'textarea'): ?>
                                <textarea class="form-control" 
                                          name="<?php echo $field['name']; ?>" 
                                          rows="4" 
                                          <?php echo $required; ?> 
                                          <?php echo $readonly; ?>
                                          placeholder="Ingrese <?php echo strtolower($field['label']); ?>"><?php echo $value; ?></textarea>

                            <?php elseif ($type === 'select'): ?>
                                <select class="form-control" name="<?php echo $field['name']; ?>" <?php echo $required; ?>>
                                    <?php foreach ($options as $val => $text): 
                                        $optionValue = is_string($val) || $val !== $text ? $val : $text;
                                        if(is_int($val) && $val === array_search($text, $options)) {
                                            $optionValue = $text;
                                        } else {
                                            $optionValue = $val;
                                        }
                                        $selected = ($value == $optionValue) ? 'selected' : '';
                                    ?>
                                        <option value="<?php echo $optionValue; ?>" <?php echo $selected; ?>>
                                            <?php echo $text; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                            <?php elseif ($type === 'datalist'): ?>
                                <?php 
                                    // Generar un ID único para el datalist basado en el nombre del campo
                                    $datalistId = 'datalist_' . $field['name'];
                                    $dataHidden = isset($field['data-hidden']) ? $field['data-hidden'] : '';
                                ?>
                                <input type="text" 
                                       class="form-control" 
                                       name="<?php echo $field['name']; ?>" 
                                       list="<?php echo $datalistId; ?>"
                                       data-hidden="<?php echo $dataHidden; ?>"
                                       value="<?php echo $value; ?>" 
                                       <?php echo $required; ?> 
                                       <?php echo $readonly; ?>
                                       autocomplete="off"
                                       placeholder="Escriba para buscar <?php echo strtolower($field['label']); ?>">
                                <datalist id="<?php echo $datalistId; ?>">
                                    <?php foreach ($options as $id => $text): ?>
                                        <option value="<?php echo $text; ?>" data-id="<?php echo $id; ?>"></option>
                                    <?php endforeach; ?>
                                </datalist>

                            <?php else: ?>
                                <input type="<?php echo $type; ?>" 
                                       class="form-control" 
                                       name="<?php echo $field['name']; ?>" 
                                       value="<?php echo $value; ?>" 
                                       <?php echo $required; ?> 
                                       <?php echo $readonly; ?> 
                                       <?php echo $step; ?>
                                       <?php echo $pattern; ?>
                                       <?php echo $title; ?>
                                       placeholder="Ingrese <?php echo strtolower($field['label']); ?>">
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <?php endforeach; ?>

                    </div> <!-- Fin row -->
                    
                    <div class="mt-3">
                        <button class="btn btn-outline-success my-2 my-sm-0 btn-block" type="submit">Ingresar</button> 
                    </div>

                </div> <!-- Fin alert -->
            </div> <!-- Fin col-12 -->
        </form>
    </div> <!-- Fin page-content -->
</div> <!-- Fin container -->
<p> <br> </p>


<?php
// Si existe un archivo JS con el nombre del controlador (ej: js/forms/Usuario.js), lo cargamos
$customScript = "js/formsjs/" . $controller . ".js";
if (file_exists($customScript)) {
    echo '<script src="' . $customScript . '"></script>';
}
?>

<!-- Incluimos la lista debajo -->
<?php 
// Aseguramos que existan las variables para la lista antes de incluirla
if (isset($registros) && isset($headers) && isset($keys)) {
    require_once('views/generic/list.php'); 
}
?>
