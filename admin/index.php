<?php 

require '../includes/funciones.php';

session_start();

if(!$_SESSION['login']) {
    header('Location: /');
}

// importar la conexion
require '../includes/config/dataBase.php';
$db = conectarDb();


// escribir el query
$query = "SELECT * FROM propiedades";


// consultar la base de datos
$resultadoConsulta = mysqli_query($db, $query);


// muestra un mensaje condicional
$Resultado = $_GET['Resultado'] ?? null;


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if($id){

        //eliminar el archivo
        $query = "SELECT Imagen FROM propiedades WHERE id = $id";

        $resultado = mysqli_query($db, $query);
        $propiedad = mysqli_fetch_assoc($resultado);

        unlink('../imagenes/' . $propiedad['Imagen']);

        //eliminar la propiedad
        $query = "DELETE FROM propiedades WHERE id = $id";
        $resultado = mysqli_query($db, $query);

        if($resultado){
            header('location: /admin?Resultado=3');
        }
    }
}

// incluye el template

incluirTemplate('header'); //include 'includes/templates/header.php';

?>

    <main class="contenedor seccion">
        <h1>Administrador de Bienes Raices</h1>

        <?php if(intval($Resultado) === 1 ): ?>
            <p class="alerta exito">Anuncio Creado Correctamente</p>

            <?php elseif(intval($Resultado) === 2 ): ?>
                <p class="alerta exito">Anuncio Actualizado Correctamente</p>

                <?php elseif(intval($Resultado) === 3 ): ?>
                    <p class="alerta exito">Anuncio Eliminado Correctamente</p>
        <?php endif; ?>
        
        <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a> 

        <table class="propiedades">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titulo</th>
                    <th>Imagen</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody> <!-- mostrar los resultados-->
                <?php while( $propiedad = mysqli_fetch_assoc($resultadoConsulta)): ?>
                <tr>
                    <td><?php echo $propiedad['id']; ?></td>
                    <td><?php echo $propiedad['Titulo']; ?></td>
                    <td><img src="/imagenes/<?php echo $propiedad['Imagen']; ?>" class="imagen-tabla"></td>
                    <td>$ <?php echo $propiedad['Precio']; ?></td>
                    <td>
                        <form method="POST" class="w-100">
                            <input type="hidden" name="id" value="<?php echo $propiedad['id']; ?>">
                            <input type="submit" class="boton-rojo-block" value="Eliminar">
                        </form>
                        <a href="admin/propiedades/actualizar.php?id=<?php echo $propiedad['id']; ?>" class="boton-amarillo-block">Actualizar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>

    <?php  
    
    // cerrar la conexion
    mysqli_close($db);
    
    incluirTemplate('footer'); ?>
