<?php

// Validar la URL por ID valido
$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if(!$id){
    header('Location: /admin');
}

//Base de datos
require '../../includes/config/database.php'; 
$db = conectarDB();

// Obtener los datos de la propiedad
$consulta = "SELECT * FROM helados WHERE id = ".$id;
$resultado = mysqli_query($db,$consulta);
$helados = mysqli_fetch_assoc($resultado);

//Consultar para obtener vendedores
$consulta = "SELECT * FROM helados";
$resultado = mysqli_query($db,$consulta);

//Arreglo con mensajes de errores
$errores=[];

$nombre = $helados['nombre'];
$precio = $helados['precio'];
$descripcion = $helados['descripcion'];
// Por seguridad la imagen no se debe de llenar en actualizar
$imagenHelados = $helados['imagen'];  

$creado = date('Y/m/d');   

//Ejecutar el codigo despues de que el usuario envie el formulario
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // echo "<pre>";
    // var_dump($_POST);   
    // echo "</pre>";

    // echo "<pre>";
    // var_dump($_FILES);   
    // echo "</pre>";
    // exit;

    $titulo = mysqli_real_escape_string($db, $_POST["nombre"]);
    $precio = mysqli_real_escape_string($db, $_POST["precio"]);
    $descripcion = mysqli_real_escape_string($db, $_POST["descripcion"]);

    //Asignar files hacia la variable
    $imagen = $_FILES['imagen'];
    

    if(!$nombre){
        $errores[] = "Debes añadir un titulo";
    }
    if(!$precio){
        $errores[] = "Debes añadir un precio";
    }
    if(strlen($descripcion) <10){
        $errores[] = "Debes añadir un descripcion y debes tener almenos 10 caracteres";
    }
    
    // if(!$imagen['name'] || $imagen['error']){
    //     $errores[]= "La imagen es obligatoria";
    // }

    // Validar por tamaño (100 kb maximo)
    $medida = 1000 * 100;
    if($imagen['size'] > $medida){
        $errores[] = "La imagen es muy pesada";
    }

    // echo "<pre>";
    // var_dump($errores);
    // echo "</pre>";

    // Revisar que el arreglo de errores este vacío
    if( empty($errores) ) {

        // // Crear carpeta 
        $carpetaImagenes = '../../imagenes/';

        if(!is_dir($carpetaImagenes)){
           mkdir($carpetaImagenes);
         }

        $nombreImagen = '';


        /* SUBIDA DE ARCHIVOS */
        if($imagen['name']){
            // Eliminar la imagen previa
            unlink($carpetaImagenes . $helados['imagen']);

             // Generar imagen nombre unico
            $nombreImagen = md5(uniqid(rand(),true)).".jpg";

            // Subir la imagen
            move_uploaded_file($imagen['tmp_name'], $carpetaImagenes.$nombreImagen);
        } else{
            $nombreImagen = $helados['imagen'];
        }           

       
    //Insertar en BD
    $query = "UPDATE helados SET nombre = '{$nombre}', precio = {$precio}, imagen='{$nombreImagen}', descripcion = '{$descripcion}' WHERE id = {$id} ";
    // echo $query;

    $resultado = mysqli_query($db,$query);

        if($resultado){
            // Redirecciona al usuario
            header('Location: /admin?mensaje=2');
        }
    }
}   
 
    include "../../includes/template/header.php";
?>
 
 <main class="contenedor seccion">
        <h1>ACtualizar helado</h1>
        <a href="/admin" class="boton boton-verde">Volver</a>

        <?php foreach($errores as $error): ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>

        <form class="formulario" method="POST" enctype="multipart/form-data">
            <fieldset>
                <legend>Informacion general</legend>

                <label for="">nombre:</label>
                <input type="text" id="nombre" name="nombre" placeholder="Nombre helado" value="<?php echo $nombre ?>">
                <label for="">Precio:</label>

     <!-- Por seguridad la imagen no se debe de llenar en actualizar -->

                <input type="number" id="precio" name="precio" placeholder="Precio helado" value="<?php echo $precio ?>">
                <label for="">Imagen:</label>

                <img src="/imagenes/<?php echo $imagenHelados; ?>" class="imagen-small" alt="">

                <input type="file" id="imagen" name="imagen" accept="image/jpeg, image/png">
                <label for="descripcion">Descripcion:</label>
                <textarea id="descripcion" name="descripcion" cols="30" rows="10" ><?php echo $descripcion ?></textarea>
            </fieldset>

            <input type="submit" value="Actualizar helado" class="boton boton-verde">
        </form>

    </main>


<?php
   include "../../includes/template/footer.php";
?>