<?php 

include "includes/template/header.php"; 

//Base de datos
require 'includes/config/database.php'; 
$db = conectarDB();

//Consultar para obtener helados
$consulta = "SELECT * FROM helados";
$resultadoDB = mysqli_query($db,$consulta);

?>

    <!-- Header Start -->
    <div class="jumbotron jumbotron-fluid page-header" style="margin-bottom: 90px;">
        <div class="container text-center py-5">
            <h1 class="text-white display-3 mt-lg-5">Product</h1>
            <div class="d-inline-flex align-items-center text-white">
                <p class="m-0"><a class="text-white" href="">Home</a></p>
                <i class="fa fa-circle px-3"></i>
                <p class="m-0">Product</p>
            </div>
        </div>
    </div>
    <!-- Header End -->


    <!-- Products Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <h1 class="section-title position-relative text-center mb-5">Best Prices We Offer For Ice Cream Lovers</h1>
                </div>
            </div>
            <div class="row">

        <?php while( $helados = mysqli_fetch_assoc($resultadoDB)): ?>

                <div class="col-lg-3 col-md-6 mb-4 pb-2">
                    <div class="product-item d-flex flex-column align-items-center text-center bg-light rounded py-5 px-3">
                        <div class="bg-primary mt-n5 py-3" style="width: 80px;">
                            <h4 class="font-weight-bold text-white mb-0">S/<?php echo $helados['precio']; ?></h4>
                        </div>
                        <div class="position-relative bg-primary rounded-circle mt-n3 mb-4 p-3" style="width: 150px; height: 150px;">
                            <img class="rounded-circle w-100 h-100" src="imagenes/<?php echo $helados['imagen']; ?>" style="object-fit: cover;">
                        </div>
                        <h5 class="font-weight-bold mb-4"><?php echo $helados['nombre']; ?></h5>
                        <a href="" class="btn btn-sm btn-secondary">Order Now</a>
                    </div>
                </div>

        <?php endwhile; ?>


                <div class="col-12 text-center">
                    <a href="" class="btn btn-primary py-3 px-5">Load More</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Products End -->


    <?php include "includes/template/footer.php"; ?>