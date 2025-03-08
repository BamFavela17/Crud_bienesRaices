<?php

$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: /');
}
// importar la conexion
require 'includes/config/database.php';
$db = conectarDB();

// Consultar
$query = "SELECT * FROM propiedades WHERE id = $id";

// Obtener resultado
$resultado = mysqli_query($db, $query);
$propiedad = mysqli_fetch_assoc($resultado);

if ($resultado->num_rows === 0) {
    header('Location: /');
}
require 'includes/funciones.php';

incluirTemplate('header');
?>

    <main class="contenedor seccion contenido-centrado">
        <h1><?php echo $propiedad['Titulo']; ?></h1>

            <img loading="lazy" src="/imagenes/<?php echo $propiedad['Imagen']; ?>" alt="imagen de la propiedad">

        <div class="resumen-propiedad">
            <p class="precio">$<?php echo $propiedad['Precio']; ?></p>
            <ul class="iconos-caracteristicas">
                <li>
                    <img class="icono" loading="lazy" src="build/img/icono_wc.svg" alt="icono wc">
                    <p><?php echo $propiedad['Wc']; ?></p>
                </li>
                <li>
                    <img class="icono" loading="lazy" src="build/img/icono_estacionamiento.svg" alt="icono estacionamiento">
                    <p><?php echo $propiedad['Estacionamiento']; ?></p>
                </li>
                <li>
                    <img class="icono"  loading="lazy" src="build/img/icono_dormitorio.svg" alt="icono habitaciones">
                    <p><?php echo $propiedad['Habitaciones']; ?></p>
                </li>
            </ul>

            <p><?php echo $propiedad['Descripcion']; ?></p>

        </div>
    </main>

<?php

    mysqli_close($db);
    incluirTemplate('footer');
?>