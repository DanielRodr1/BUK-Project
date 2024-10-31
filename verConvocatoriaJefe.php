<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.15.4/css/all.css"
        integrity="sha384-4C2PxmMj1ZI8Y+9H54uN5IsZ6pJuhtO5YOb7WnHk/KR4J6XGz9znTyy7RzyffsVH" crossorigin="anonymous" />
    <link rel="stylesheet" href="public/css/verConvocatoriaJefe.css" />
    <title>ver convocatoria</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="homeJefe.html"><img src="img/logo.png" alt="logo de la empresa"
                    class="img-fluid logoEmpresa"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="homeJefe.html">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="verConvocatoriaJefe.php">Convocatorias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="abrirConvocatoria.php">Crear Convocatoria</a>
                    </li>
                </ul>

                <!-- Nuevo elemento con el ícono a la derecha -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <form action="Clases/cerrarSesion.php" method="post" class="nav-link">
                            <button type="submit" class="btn btn-link">
                                <i class="far fa-user-circle fa-2x"></i> Cerrar Sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <h1>CONVOCATORIAS</h1>

    <div class="contMain container">

        <?php
            $tipoInterfaz = 'jefe';
            include_once 'Clases/Convocatoria.php';
            $convocatoria = new Convocatoria();
            $resultadoConvocatorias = $convocatoria->Listar_convocatorias($tipoInterfaz);

            if ($resultadoConvocatorias !== false && !empty($resultadoConvocatorias)) {
                foreach ($resultadoConvocatorias as $convocatoriaxd) {
                    $htmlConvocatoria = $convocatoriaxd['html'];
                    echo $htmlConvocatoria;
                    echo $convocatoria->listar_bases($convocatoriaxd['announcement_id']);
                }
            } else {
                echo '<div class="d-flex justify-content-center align-items-center" style="height: 60vh;">
                        <h2 class="text-center">NO HAY CONVOCATORIAS VIGENTES</h2>
                    </div>';
            }
        ?>

        <!-- Modal para "Concurso Finalizado" -->
        <div class="modal fade" id="finConcursoModal" tabindex="-1" aria-labelledby="finConcursoModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="finConcursoModalLabel">Fin de la convocatoria</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>La convocatoria ha finalizado.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        ola <br> ol
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/761684499b.js" crossorigin="anonymous"></script>
    

    <script>
        // Función para redirigir a verPostulantes.html
        function redirigirVerPostulantes(idannouncement) {
            window.location.href = "verPostulantes.php?idannouncement=" + idannouncement;
        }
    </script>

</body>

</html>