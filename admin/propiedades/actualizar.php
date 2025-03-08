<?php

require '../../includes/funciones.php';

if (!estaAutenticado()) {
    header('Location: /');
}

// validar la URL por ID valido
$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT); // filtrado de valor para aceptar solo numeros enteros

if(!$id){
    header('location: /admin');
}

// base de datos
require '../../includes/config/dataBase.php';
$db = conectarDb();

// Consultar para obtener la propiedades
$consulta = "SELECT * FROM propiedades where id = $id";
$resultado = mysqli_query($db, $consulta);
$propiedad = mysqli_fetch_assoc($resultado);

//  echo "<pre>";
//  var_dump($propiedad);
//  echo "</pre>";

//consultar para obtener todos los vendedores
$consulta = "SELECT * FROM vendedores";
$resultado = mysqli_query($db, $consulta);

//Arreglo de errores
$errores = [];

// variables del formulario
$titulo = $propiedad['Titulo'];
$precio = $propiedad['Precio'];
$descripcion = $propiedad['Descripcion'];
$habitaciones = $propiedad['Habitaciones'];
$wc = $propiedad['Wc'];
$estacionamiento = $propiedad['Estacionamiento'];
$vendedorId = $propiedad['vendedores_id'];
$iamagenPropiedad = $propiedad['Imagen'];

/// ejecutar el codigo despues de qwue el usuaro envia el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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

   //var_dump($imagen['name']);
   //var_dump($imagen['size']);

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
        // validar por tamano (100kb maximo)
        $medida = 1000 * 100;
        if(!$imagen['size'] > $medida ){
            $errores[] = "La imagen es muy pesada";
        }

    /* echo "<pre>";
     var_dump($errores);
     echo "</pre>";
    */

    // revisar que el array de errores este vacio

    if (empty($errores)) {

        /** subida de archivos **/

        // crear carpeta
         $carpetaImagenes = '../../imagenes/';
  
         if(!is_dir($carpetaImagenes)){
  
             mkdir($carpetaImagenes);
         }// fin insercion

         $nombreImagen = '';
      if($imagen['name']){
      // eliminar imagen previa

      unlink($carpetaImagenes . $propiedad['Imagen']);
       // generar un nombre unico
         $nombreImagen = md5( uniqid( rand(), true ) ) . ".jpg" ;

        // subir imagen
         move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen );
      }else{
        $nombreImagen = $propiedad['Imagen'];
      }
      
        
 
        // Actualizar en la base de datos
        $query = "UPDATE propiedades SET Titulo = '$titulo', Precio = $precio, Imagen = '$nombreImagen', Descripcion = '$descripcion', 
        Habitaciones = $habitaciones,  Wc = $wc, Estacionamiento = $estacionamiento,  vendedores_id = $vendedorId WHERE id = $id";

         //echo $query;
         //exit;

        //insercion de datos en la db
        $resultado = mysqli_query($db, $query);

        if ($resultado) {
            // redireccionar al usuario.
            header('Location: /admin?Resultado=2'); // mandar un resultado
        } 
    }
}


incluirTemplate('header'); //include 'includes/templates/header.php';

?>

<main class="contenedor seccion">
    <h1>Actualizar Propiedad</h1>

    <a href="/admin" class="boton boton-verde">Volver</a>

    <?php foreach ($errores as $error): ?>
        <div class="alerta error">
            <?php echo $error ?>

        </div>
        <?php endforeach; 
       // action="/admin/propiedades/actualizar.php"
        ?> 

        <form method="POST" class="formulario" enctype="multipart/form-data">

        <fieldset>
            <legend>Infromacion General</legend>

            <label for="titulo">Titulo:</label>
            <input type="text" id="titulo" name="titulo" placeholder="Titulo propiedad" value="<?php echo $titulo; ?>">

            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" placeholder="Precio propiedad" value="<?php echo $precio; ?>">

            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

            <img src="/imagenes/<?php echo $iamagenPropiedad; ?>" alt="No cuenta con imagen descriptiva" class="imagen-small">

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

        <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">

    </form>

</main>

<?php incluirTemplate('footer'); ?>