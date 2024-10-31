<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-4C2PxmMj1ZI8Y+9H54uN5IsZ6pJuhtO5YOb7WnHk/KR4J6XGz9znTyy7RzyffsVH" crossorigin="anonymous"/>
    <link rel="stylesheet" href="public/css/verConvocatoriaNoLogin.css"/>
    <title>ver convocatoria</title>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="Index.html"><img src="img/logo.png" alt="logo de la empresa" class="img-fluid logoEmpresa"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="Index.html">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="verConvocatoriaNoLogin.php">Convocatorias</a>
                    </li>
                </ul>
                
                <!-- Nuevo elemento con el ícono a la derecha -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="Login.php">
                            <i class="far fa-user-circle fa-2x"> Iniciar Sesión</i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <h1>CONVOCATORIAS</h1>

    <div class="contMain container">
        <?php
            $tipoInterfaz = 'nologin';
            include_once 'Clases/Convocatoria.php'; // Asegúrate de poner la ruta correcta
            $convocatoria = new Convocatoria();
            $resultadoConvocatorias = $convocatoria->Listar_convocatorias($tipoInterfaz);

            // Verificar si se obtuvieron convocatorias correctamente y si hay convocatorias disponibles
            if ($resultadoConvocatorias !== false && !empty($resultadoConvocatorias)) {
                // Iterar sobre las convocatorias
                foreach ($resultadoConvocatorias as $convocatoriaxd) {
                    // Acceder a los valores
                    $htmlConvocatoria = $convocatoriaxd['html'];
                    $idConvocatoria = $convocatoriaxd['announcement_id'];
                    echo $htmlConvocatoria;
                    echo $convocatoria->listar_bases($idConvocatoria);
                }
            } else {
                // Mostrar el mensaje cuando no haya convocatorias vigentes
                echo '<div class="d-flex justify-content-center align-items-center" style="height: 60vh;">
                        <h2 class="text-center">NO HAY CONVOCATORIAS VIGENTES</h2>
                      </div>';
            }
        ?>

        

        <!-- Modal NECESITAS ESTAR LOGEADO -->
        <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center w-100" id="loginModalLabel">NECESITAS ESTAR LOGEADO</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Okey, entiendo</button>
                        <a href="Login.php" class="btn btn-primary">Ir al Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        ola <br> ol
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/761684499b.js" crossorigin="anonymous"></script>

</body>
</html>