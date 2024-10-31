<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-4C2PxmMj1ZI8Y+9H54uN5IsZ6pJuhtO5YOb7WnHk/KR4J6XGz9znTyy7RzyffsVH" crossorigin="anonymous"/>
    <link rel="stylesheet" href="public/css/verPostulantes.css"/>
    <title>Postulantes</title>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="homeJefe.html"><img src="img/logo.png" alt="logo de la empresa" class="img-fluid logoEmpresa"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
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
                        <a class="nav-link active" href="abrirConvocatoria.html">Crear Convocatoria</a>
                    </li>
                </ul>
                
                <!-- Nuevo elemento con el ícono a la derecha -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <form action="Clases/cerrarSesion.php" method="post" class="nav-link">
                            <button type="submit" class="btn">
                                <i class="far fa-user-circle fa-2x"></i> Cerrar Sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1 class="mt-3" style="color: #009EE2; text-align: center;">Postulantes</h1>
        <div class="d-flex justify-content-between mb-3">
            <a href="verConvocatoriaJefe.php" class="btn btn-dark"><i class="fas fa-arrow-left"></i> Atrás</a>
            <!-- Botón para ordenar las filas -->
            <button class="btn btn-dark" onclick="ordenarFilas()">Ordenar</button>
        </div>

        <!-- Tabla de postulantes con barra de desplazamiento vertical -->
        <div class="table-container">
            <table class="table table-bordered" id="tablaPostulantes">
                <thead>
                    <tr>
                        <th>Nro</th>
                        <th>Apellidos</th>
                        <th>Nombres</th>
                        <th>Nro de Celular</th>
                        <th>Puntaje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        require_once __DIR__ . '/Clases/Convocatoria.php'; // Asegúrate de poner la ruta correcta

                        $convocatoria = new Convocatoria();
                        if (isset($_GET['idannouncement'])) {
                            $announcement_id = $_GET['idannouncement'];
                        } else {
                        
                            die("ID de convocatoria no proporcionado");
                        }   
                        $postulantes = $convocatoria->obtenerPostulantesPorConvocatoria($announcement_id);

                        // Si no hay postulantes, mostrar el mensaje "NO HAY POSTULANTES AÚN"
                        if (empty($postulantes)) {
                            echo '<div class="text-center mt-5" style="font-size: 24px; font-weight: bold; color: #ff0000;">NO HAY POSTULANTES AÚN</div>';
                        } else {
                            

                            foreach ($postulantes as $key => $postulante) {
                                echo '<tr>';
                                echo '<td>' . ($key + 1) . '</td>';
                                echo '<td>' . $postulante['lastname'] . '</td>';
                                echo '<td>' . $postulante['name'] . '</td>';
                                echo '<td>' . $postulante['telephone'] . '</td>';
                                echo '<td>' . $postulante['puntaje'] . '</td>';
                                echo '</tr>';
                            }

                            echo '</tbody>';
                            echo '</table>';
                            echo '</div>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/761684499b.js" crossorigin="anonymous"></script>
    <script>
        // Función para ordenar las filas de la tabla por puntaje de mayor a menor
        function ordenarFilas() {
            var tabla = document.getElementById("tablaPostulantes").getElementsByTagName('tbody')[0];
            var filas = [].slice.call(tabla.getElementsByTagName("tr"));
            
            filas.sort(function(a, b) {
                var puntajeA = parseInt(a.cells[4].innerText);
                var puntajeB = parseInt(b.cells[4].innerText);
                return puntajeB - puntajeA;
            });
            
            filas.forEach(function(fila) {
                tabla.appendChild(fila);
            });
        }
    </script>

</body>
</html>
