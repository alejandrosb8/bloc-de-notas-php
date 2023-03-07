<?php

$modo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modo = $_POST['modo'];
    $nombre = $_POST['nombre'];
    $contenido = $_POST['contenido'];

    if ($modo === 'crear') {
        // Verificar que se proporcionó un nombre de archivo
        if (empty($nombre)) {
            $mensaje = 'Error: debe proporcionar un nombre de archivo';
        } else {
            // Crear el archivo
            $archivo = fopen('files/' . $nombre . '.txt', 'w');
            if (!$archivo) {
                $mensaje = 'Error: no se pudo crear el archivo';
            } else {
                // Escribir el contenido en el archivo
                if (!empty($contenido)) {
                    fwrite($archivo, $contenido);
                }

                // Cerrar el archivo
                fclose($archivo);

                $mensaje = 'El archivo se ha creado correctamente';
            }
        }
    } elseif ($modo === 'abrir') {
        // Verificar que se proporcionó un nombre de archivo
        if (empty($nombre)) {
            $mensaje = 'Error: debe proporcionar un nombre de archivo';
        } else {
            // Verificar que el archivo existe
            $archivo = 'files/' . $nombre . '.txt';
            if (!file_exists($archivo)) {
                $mensaje = 'Error: el archivo no existe';
            } else {
                // Leer el contenido del archivo
                $contenido = file_get_contents($archivo);
            }
        }
    } elseif ($modo === 'borrar') {
        // Verificar que se proporcionó un nombre de archivo
        if (empty($nombre)) {
            $mensaje = 'Error: debe proporcionar un nombre de archivo';
        } else {
            // Verificar que el archivo existe
            $archivo = 'files/' . $nombre . '.txt';
            if (!file_exists($archivo)) {
                $mensaje = 'Error: el archivo no existe';
            } else {
                // Borrar el archivo
                if (!unlink($archivo)) {
                    $mensaje = 'Error: no se pudo borrar el archivo';
                } else {
                    $mensaje = 'El archivo se ha borrado correctamente';
                }
            }
        }
    } elseif ($modo === 'guardar') {
        // Verificar que se proporcionó un nombre de archivo
        if (empty($nombre)) {
            $mensaje = 'Error: debe proporcionar un nombre de archivo';
        } else {
            // Verificar que el archivo existe
            $archivo = 'files/' . $nombre . '.txt';
            if (!file_exists($archivo)) {
                $mensaje = 'Error: el archivo no existe';
            } else {
                // Escribir el contenido en el archivo
                $archivo = fopen($archivo, 'w');
                if (!$archivo) {
                    $mensaje = 'Error: no se pudo abrir el archivo para escritura';
                } else {
                    fwrite($archivo, $contenido);

                    // Cerrar el archivo
                    fclose($archivo);

                    $mensaje = 'El archivo se ha guardado correctamente';
                }
            }
        }
    }
}

// Obtener la lista de archivos existentes
$archivos = array_diff(scandir('files'), array('..', '.'));

?>
<!DOCTYPE html>
<html lang="es" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloc de notas</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.*/css/pico.min.css">
</head>

<body>
    <main class="container">
        <h1>Bloc de notas</h1>



        <form method="post">
            <label for="modo">Selecciona el modo</label>
            <select name="modo" id="modo">
                <option value="crear" <?php if ($modo === 'crear') {
                                            echo 'selected';
                                        } ?>>Crear</option>
                <option value="abrir" <?php if ($modo === 'abrir') {
                                            echo 'selected';
                                        } ?>>Abrir</option>
                <option value="guardar" <?php if ($modo === 'guardar') {
                                            echo 'selected';
                                        } ?>>Guardar</option>
                <option value="borrar" <?php if ($modo === 'borrar') {
                                            echo 'selected';
                                        } ?>>Borrar</option>

            </select>
            <?php if (isset($mensaje)) { ?>
                <mark>*<?php echo $mensaje; ?></mark>
            <?php } ?>
            <br><br>
            <label for="nombre">Nombre de archivo:</label>
            <input type="text" name="nombre" id="nombreArchivo">
            <br><br>
            <?php if (isset($contenido)) { ?>
                <textarea style="resize: none; min-height: 400px" name="contenido"><?php echo $contenido; ?></textarea>
            <?php } else { ?>
                <textarea style="resize: none; min-height: 400px" name="contenido"></textarea>
            <?php } ?>
            <br><br>
            <input type="hidden" name="modo_seleccionado" value="<?php echo $modo; ?>">

            <input type="submit" value="Enviar">
        </form>

        <h2>Archivos existentes</h2>
        <ul>
            <?php foreach ($archivos as $archivo) { ?>
                <?php $nombreArchivo = basename($archivo, '.txt'); ?>
                <li style="cursor: pointer;" data-nombre="<?php echo $nombreArchivo; ?>" onclick="seleccionarArchivo(this)"><?php echo $nombreArchivo; ?></li>
            <?php } ?>
        </ul>
    </main>


</body>
<script>
    function seleccionarArchivo(nombreArchivo) {
        document.getElementById('nombreArchivo').value = nombreArchivo.textContent;
    }
</script>

</html>
