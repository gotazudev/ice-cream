<?php
    //Base de datos
    require '../../includes/config/database.php'; 
    $db = conectarDB();

    //Consultar para obtener vendedores
    $consulta = "SELECT * FROM helados";
    $resultado = mysqli_query($db,$consulta);

    //Arreglo con mensajes de errores
    $errores=[];

    $nombre = '';
    $precio = '';
    $descripcion = '';
    $creado = date('Y/m/d');


    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        $nombre = mysqli_real_escape_string($db, $_POST["nombre"]);
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
        if(!$imagen['name'] || $imagen['error']){
            $errores[]= "La imagen es obligatoria";
        }
        // Validar por tamaño (100 kb maximo)
        $medida = 1000 * 100;
        if($imagen['size'] > $medida){
            $errores[] = "La imagen es muy pesada";
        }

        // Revisar que el arreglo de errores este vacío
        if( empty($errores) ) {
            
            // Crear carpeta 
            $carpetaImagenes = '../../imagenes/';
 
            if(!is_dir($carpetaImagenes)){
                mkdir($carpetaImagenes);
            }

            $nombreImagen = md5(uniqid(rand(),true)).".jpg";

            move_uploaded_file($imagen['tmp_name'], $carpetaImagenes.$nombreImagen);

               //Insertar en BD
            $query = "INSERT INTO helados (nombre, precio, imagen, descripcion, creado ) VALUES ('$nombre', '$precio', '$nombreImagen','$descripcion','$creado')";
            // echo $query;
            $resultado = mysqli_query($db,$query); 

            if($resultado){
                // Redirecciona al usuario
                header('Location: /admin?mensaje=1');
            }
        }
    }  
    

    include "../../includes/template/header.php";

?>


<main class="contenedor seccion">
        <h1>Crear</h1>
        <a href="/admin">Volver</a>

        <?php foreach($errores as $error): ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>

        <form class="formulario" method="POST" action="/admin/helados/crear.php" enctype="multipart/form-data">
            <fieldset>
                <legend>Informacion general</legend>

                <label for="">Nombre:</label>
                <input type="text" id="nombre" name="nombre" placeholder="Nombre helado" value="<?php echo $nombre ?>">
                <label for="">Precio:</label>
                <input type="number" id="precio" name="precio" placeholder="Precio helado" value="<?php echo $precio ?>">
                <label for="">Imagen:</label>
                <input type="file" id="imagen" name="imagen" accept="image/jpeg, image/png">
                <label for="descripcion">Descripcion:</label>
                <textarea id="descripcion" name="descripcion" cols="30" rows="10" ><?php echo $descripcion ?></textarea>
            </fieldset>

            <input type="submit" value="Crear helado" class="boton boton-verde">
        </form>

    </main>
 
<?php
   include "../../includes/template/footer.php";
?>