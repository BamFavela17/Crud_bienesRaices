<?php 
    // Importar la conexión
    require __DIR__ . '/../config/database.php';
    $db = conectarDB();


    // consultar
    $query = "SELECT * FROM propiedades LIMIT $limite";

    // obtener resultado
    $resultado = mysqli_query($db, $query);


?>

<div class="contenedor-anuncios">
        <?php while($propiedad = mysqli_fetch_assoc($resultado)): ?>
        <div class="anuncio">

            <img loading="lazy" src="/imagenes/<?php echo $propiedad['Imagen']; ?>" alt="anuncio">

            <div class="contenido-anuncio">
                <h3><?php echo $propiedad['Titulo']; ?></h3>
                <p><?php echo $propiedad['Descripcion']; ?></p>
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
                        <img class="icono" loading="lazy" src="build/img/icono_dormitorio.svg" alt="icono habitaciones">
                        <p><?php echo $propiedad['Habitaciones']; ?></p>
                    </li>
                </ul>

                <a href="anuncio.php?id=<?php echo $propiedad['id']; ?>" class="boton-amarillo-block">
                    Ver Propiedad
                </a>
            </div><!--.contenido-anuncio-->
        </div><!--anuncio-->
        <?php endwhile; ?>
    </div> <!--.contenedor-anuncios-->

<?php 

    // Cerrar la conexión
    mysqli_close($db);
?>