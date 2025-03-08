<?php

require '../../includes/funciones.php';

if (!estaAutenticado()) {
    header('Location: /');
}

// base de datos
require '../../includes/config/dataBase.php';
$db = conectarDb();

//consultar para obtener todos los vendedores
$consulta = "SELECT * FROM vendedores";
$resultado = mysqli_query($db, $consulta);

//Arreglo de errores
$errores = [];

// variables del formulario
$titulo = "";
$precio = "";
$descripcion = "";
$habitaciones = "";
$wc = "";
$estacionamiento = "";
$vendedorId = "";

/// ejecutar el codigo despues de qwue el usuaro envia el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   // echo "<pre>";
    //var_dump($_POST);
  //  var_dump($_FILES); // permite ver el contenido de los archivos
   // echo "</pre>";


    // variables del formulario
    // agragado sanitizacion
    $titulo = mysqli_real_escape_string($db,$_POST['titulo']);
    $precio = mysqli_real_escape_string($db,$_POST['precio']);
    $descripcion = mysqli_real_escape_string($db,$_POST['descripcion']);
    $habitaciones = mysqli_real_escape_string($db,$_POST['habitaciones']);
    $wc = mysqli_real_escape_string($db,$_POST['wc']) ;
    $estacionamiento = mysqli_real_escape_string($db,$_POST['estacionamiento']);
    $vendedorId = mysqli_real_escape_string($db,$_POST['vendedor']);
    $creado = date('Y/m/d');

    // asignar filkes hacia una variable
    $imagen = $_FILES['imagen'];

    // validaciones de los campos
    if (!$titulo) {
        $errores[] = "El campo de titulo es obligatorio";
    }
    if (!$precio) {
        $errores[] = "El campo de precio es obligatorio";
    }
    if (strlen($descripcion) < 50) {
        $errores[] = "La descripcion no puede ser m,enoir a 50 caracteres";
    }
    if (!$habitaciones) {
        $errores[] = "DEl campo de habitaciones es obligatorio";
    }
    if (!$wc) {
        $errores[] = "El campo de banos es obligatorio";
    }
    if (!$estacionamiento) {
        $errores[] = "El campo de estacionamiento es obligatorio";
    }
    if (!$vendedorId) {
        $errores[] = "Elige un vendedor";
    }
    if(!$imagen['name']){
        $errores[] = "La imagen es obligatoria";
    }
        // validar por tamano (100kb maximo)
        $medida = 1000 * 100;
        if(!$imagen['size'] > $medida ){
            $errores[] = "La imagen es muy pesada";
        }

    /* echo "<pre>";
     var_dump($errores);
     echo "</pre>";
    */

    //exit;

    // revisar que el array de errores este vacio

    if (empty($errores)) {

        /** subida de archivos **/

        // crear carpeta
        $carpetaImagenes = '../../imagenes/';

        if(!is_dir($carpetaImagenes)){

            mkdir($carpetaImagenes);
        }// fin insercion

        // generar un nombre unico
        $nombreImagen = md5( uniqid( rand(), true ) ) . ".jpg" ;

        // subir imagen
        move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen );
 
        // insertar en la base de datos
        $query = "INSERT INTO propiedades(Titulo,Precio,Imagen,Descripcion,Habitaciones,Wc,Estacionamiento,Creado,Vendedores_id) 
        VALUES('$titulo','$precio','$nombreImagen','$descripcion','$habitaciones','$wc','$estacionamiento','$creado','$vendedorId')";

        // echo $query;

        //insercion de datos en la db
        $resultado = mysqli_query($db, $query);

        if ($resultado) {
            // redireccionar al usuario.
            header('Location: /admin?Resultado=1'); // mandar un resultado
        } 
    }
}

incluirTemplate('header'); //include 'includes/templates/header.php';

?>

<main class="contenedor seccion">
    <h1>crear</h1>

    <a href="/admin" class="boton boton-verde">Volver</a>

    <?php foreach ($errores as $error): ?>
        <div class="alerta error">
            <?php echo $error ?>

        </div>
    <?php endforeach; ?>
    <form method="POST" class="formulario" action="/admin/propiedades/crear.php" enctype="multipart/form-data">

        <fieldset>
            <legend>Infromacion General</legend>

            <label for="titulo">Titulo:</label>
            <input type="text" id="titulo" name="titulo" placeholder="Titulo propiedad" value="<?php echo $titulo; ?>">

            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" placeholder="Precio propiedad" value="<?php echo $precio; ?>">

            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

            <label for="descripcion">Descripcion</label>
            <textarea name="descripcion" id="descripcion"><?php echo $descripcion; ?></textarea>
        </fieldset>

        <fieldset>
            <legend>Infromacion de la proipiedad</legend>

            <label for="habitaciones">Habitaciones:</label>
            <input type="number" name="habitaciones" id="habitaciones" placeholder="Ej: 3" min="1" max="9" value="<?php echo $habitaciones; ?>">

            <label for="wc">Baños:</label>
            <input type="number" name="wc" id="wc" placeholder="Ej: 3" min="1" max="9" value="<?php echo $wc; ?>">

            <label for="estacionamiento">Estacionamiento:</label>
            <input type="number" name="estacionamiento" id="estacionamiento" placeholder="Ej: 3" min="1" max="9" value="<?php echo $estacionamiento; ?>">
        </fieldset>

        <fieldset>
            <legend>Vendedor</legend>

            <select name="vendedor">
                <option value="">--Selecciona--</option>
                <?php while ($row = mysqli_fetch_assoc($resultado)) : ?> <!-- iterar sobre los resultados -->
                    <option <?php echo $vendedorId === $row['id'] ? 'selected' : ''; ?> value="<?php echo $row['id'] ?>"> <!-- arow funtion obtener el id -->
                        <?php echo $row['Nombre'] . " " . $row['Apellido']; ?> <!-- mostrar el nombre y apellido obtenido -->
                    </option>
                <?php endwhile; ?><!-- fin de iteracion -->
            </select>
        </fieldset>

        <input type="submit" value="Crear Propiedad" class="boton boton-verde">

    </form>

</main>

<?php incluirTemplate('footer'); ?>