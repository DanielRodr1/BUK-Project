<?php
$duplicada = isset($_GET['error']) && $_GET['error'] === 'duplicada';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-4C2PxmMj1ZI8Y+9H54uN5IsZ6pJuhtO5YOb7WnHk/KR4J6XGz9znTyy7RzyffsVH" crossorigin="anonymous"/>
    <link rel="stylesheet" href="public/css/abrirConvocatoria.css"/>
    <title>Aperturar Convocatorias</title>
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
                            <button type="submit" class="btn btn-link">
                                <i class="far fa-user-circle fa-2x"></i> Cerrar Sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        
        <a href="verConvocatoriaJefe.php">
            <button class="btn" type="button" style="font-size: 17px; font-weight: bolder; text-decoration: underline;">
                << Regresar
            </button>
        </a>
        <h1>Abrir Concurso de Convocatoria</h1>
        <h2>CONCURSO DE POSTULACIÓN</h2>
        
        <form id="formularioConvocatoria" class="form-inline formOne" action="Clases/guardarConvocatoria.php" method="post">
            <div class="rowF0 row">
                <div class="col-md-4 text-md-end align-self-center">
                    <label for="workArea">Área de Trabajo: </label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control w-50" id="workArea" placeholder="Ejemplo: Asistente de contabilidad" name="workArea" required maxlength="150">
                </div>
            </div>
            
            <div class="rowF1 row">
                <div class="col-md-4 text-md-end align-self-center">
                    <label for="salary">Salario: </label>
                </div>
                <div class="col-md-8">
                    <input type="number" class="form-control w-50" id="salary" placeholder="Ejemplo: 15000" required oninput="validatePositiveNumber(this)" name="salary" maxlength="150">
                    <!-- Agrega un div para mostrar el mensaje de error -->
                    <div id="salaryError" class="error-message"></div>
                </div>
            </div>
            
            <div class="rowF20 row">
                <div class="col-md-4 text-md-end align-self-center">
                    <label for="modality">Modalidad: </label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control w-50" id="modality" placeholder="Remoto o Presencial" name="modality" required maxlength="150">
                </div>
            </div>
            
            <div class="rowF2 row">
                <div class="col-md-4 text-md-end align-self-center">
                    <label for="timeWork">Turno de Trabajo: </label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control w-50" id="timeWork" placeholder="Parcial o Completo" name="timeWork" required maxlength="150">
                </div>
            </div>
            
            <div class="rowF3 row">
                <div class="col-md-4 text-md-end align-self-center">
                    <label for="vacancies">Nro Vacantes: </label>
                </div>
                <div class="col-md-8">
                    <input type="number" class="form-control w-50" id="vacancies" placeholder="2" name="vacancies" required oninput="validatePositiveNumber(this)" maxlength="150">
                    <!-- Agrega un div para mostrar el mensaje de error -->
                    <div id="vacanciesError" class="error-message"></div>
                </div>
            </div>
            
            <div class="rowF4 row">
                <div class="col-md-4 text-md-end align-self-center">
                    <label for="responsibilities">Responsabilidades: </label>
                </div>
                <div class="col-md-8">
                    <div id="responsabilidadesContainer">
                        <div class="input-group w-50">
                            <input type="text" class="form-control" name="responsibilities[]" placeholder="Ejemplo: 1.- Testear softwares nuevos" required maxlength="150">
                            <button type="button" class="btn" onclick="agregarCampoResponsabilidades('responsabilidadesContainer', true, 'responsibilities[]')">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="rowF5 row">
                <div class="col-md-4 text-md-end align-self-center">
                    <label for="benefits">Beneficios: </label>
                </div>
                <div class="col-md-8">
                    <div id="beneficiosContainer">
                        <div class="input-group w-50">
                            <input type="text" class="form-control" name="benefits[]" placeholder="Ejemplo: Asistente de contabilidad" maxlength="150" required>
                            <button type="button" class="btn" onclick="agregarCampoBeneficios('beneficiosContainer', true, 'benefits[]')">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <h2>BASES DE LA CONVOCATORIA</h2>
            
            <div class="rowF6 row">
                <div class="col-md-4 text-md-end align-self-center">
                    <label for="requirements">Requisitos: </label>
                </div>
                <div class="col-md-8">
                    <div id="requisitosContainer">
                        <div class="input-group w-50">
                            <input type="text" class="form-control" name="requirements[]" placeholder="Ejemplo: Egresado de Universidad" required maxlength="150">
                            <button type="button" class="btn" onclick="agregarCampo('requisitosContainer', true, 'requirements[]')">
                                <i class="fas fa-plus"></i>
                            </button>                            
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="rowF7 row">
                <div class="col-md-4 text-md-end align-self-center">
                    <label for="hiringProcess">Proceso de Contratación: </label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control w-50" id="hiringProcess" placeholder="Describir el proceso" name="hiringProcess" required maxlength="150">
                </div>
            </div>
            
            <div class="rowF8 row">
                <div class="col-md-4 text-md-end align-self-center">
                    <label for="evaluators">Comité Evaluador: </label>
                </div>
                <div class="col-md-8">
                    <div id="evaluatorsContainer">
                        <div class="input-group w-50">
                            <input type="text" class="form-control w-50" name="evaluators[]" placeholder="Ejemplo: Ing. Edward Castillo" required maxlength="150">
                            <button type="button" class="btn" onclick="agregarCampo('evaluatorsContainer', true, 'evaluators[]')">
                                <i class="fas fa-plus"></i>
                            </button>                            
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="rowF9 row">
                <div class="col-md-4 text-md-end align-self-center">
                    <label for="selectionCriteria">Criterios de Selección: </label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control w-50" id="selectionCriteria" placeholder="Ejemplo: Se escogerá al postulante de acuerdo ..." name="selectionCriteria" required maxlength="150">
                </div>
            </div>
            
            <div class="rowF10 row">
                <div class="col-md-4 text-md-end align-self-center">
                    <label for="notifyApplicant">Comunicar al Postulante: </label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control w-50" id="notifyApplicant" placeholder="Ejemplo: Asistente de contabilidad" name="notifyApplicant" required maxlength="150">
                </div>
            </div>
            
            <p>CRONOGRAMA</p>
            
            <div class="rowF11 row">
                <div class="firstCronograma row">
                    <div class="col-md-4 text-md-end align-self-center">
                        <label for="dateStart">Fecha de Inicio: </label>
                    </div>
                    <div class="col-md-8">
                        <input type="date" class="form-control w-50 fecha-input" id="dateStart" name="dateStart">
                    </div>
                </div>
            
                <div class="secondCronograma row">
                    <div class="col-md-4 text-md-end align-self-center">
                        <label for="dateLimit">Fecha Límite: </label>
                    </div>
                    <div class="col-md-8">
                        <input type="date" class="form-control w-50 fecha-input" id="dateLimit" name="dateLimit">
                    </div>
                </div>
            
                <div class="thirdCronograma row">
                    <div class="col-md-4 text-md-end align-self-center">
                        <label for="dateInterview">Fecha de Entrevista: </label>
                    </div>
                    <div class="col-md-8">
                        <input type="date" class="form-control w-50 fecha-input" id="dateInterview" name="dateInterview">
                    </div>
                </div>
            
                <div class="fourthCronograma row">
                    <div class="col-md-4 text-md-end align-self-center">
                        <label for="dateAnnouncement">Anuncio de Candidato: </label>
                    </div>
                    <div class="col-md-8">
                        <input type="date" class="form-control w-50 fecha-input" id="dateAnnouncement" name="dateAnnouncement">
                    </div>
                </div>
            </div>            
            <!--<button class="btn-1 btn btn-dark" type="button" style="width: 160px;" onclick="enviarFormulario()">Crear Preguntas</button>-->
            <button class="btn-1 btn btn-dark" type="submit" style="width: 160px;">Crear Preguntas</button>
        </form>
    </div>

    <!-- Modal para notificar que ya existe una convocatoria para el mismo puesto y fecha -->
    <div class="modal fade" id="modalErrorConvocatoria" tabindex="-1" aria-labelledby="modalErrorConvocatoriaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalErrorConvocatoriaLabel">Error al crear la convocatoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Ya existe una convocatoria para el mismo área de trabajo y fecha de inicio.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    
    <footer>
        ola <br> ol
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/761684499b.js" crossorigin="anonymous"></script>

    <?php if ($duplicada): ?>
        <!-- Mostrar el modal automáticamente si hay duplicado -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modalDuplicado = new bootstrap.Modal(document.getElementById('modalErrorConvocatoria'));
                modalDuplicado.show();
            });
        </script>
    <?php endif; ?>

    <script>
        function validatePositiveNumber(input) {
            // Convierte el valor a un número
            var value = parseFloat(input.value);

            // Obtén el div del mensaje de error correspondiente
            var errorDiv = document.getElementById(input.id + 'Error');

            // Verifica si el valor es un número y es mayor o igual a 0
            if (isNaN(value) || value <= 0) {
                // Muestra un mensaje de error en el div correspondiente
                errorDiv.innerHTML = 'Ingrese un número válido y mayor o igual a 0.';
                // Cambia el estilo para mostrar en rojo
                errorDiv.style.color = 'red';
                // Restablece el valor a vacío para obligar al usuario a corregirlo
                input.value = '';
            } else {
                // Si no hay error, borra el mensaje y restablece el estilo
                errorDiv.innerHTML = '';
                errorDiv.style.color = '';
            }
        }
    </script>

    <script>
        // Obtén la fecha actual en el formato YYYY-MM-DD
        var fechaActual = new Date().toISOString().split('T')[0];

        // Establece la fecha actual como el valor mínimo para todos los elementos con la clase 'fecha-input'
        var inputsFecha = document.getElementsByClassName('fecha-input');
        for (var i = 0; i < inputsFecha.length; i++) {
            inputsFecha[i].min = fechaActual;
            inputsFecha[i].addEventListener('change', function () {
                // Validación de fechas para garantizar que no estén en el pasado
                if (this.value < fechaActual) {
                    this.value = fechaActual;
                }
            });
        }

        // Maneja el evento onchange solo para la fecha de inicio
        document.getElementById('dateStart').addEventListener('change', function () {
            // Obtén el valor de la fecha de inicio
            var fechaInicio = new Date(this.value);

            // Establece la fecha mínima para la fecha límite (un día después de la fecha de inicio)
            document.getElementById('dateLimit').min = sumarDias(fechaInicio, 1).toISOString().split('T')[0];
        });

        // Maneja el evento onchange solo para la fecha límite
        document.getElementById('dateLimit').addEventListener('change', function () {
            // Obtén el valor de la fecha límite
            var fechaLimite = new Date(this.value);

            // Establece la fecha mínima para la fecha de entrevista (un día después de la fecha límite)
            document.getElementById('dateInterview').min = sumarDias(fechaLimite, 1).toISOString().split('T')[0];
        });

        // Maneja el evento onchange solo para la fecha de entrevista
        document.getElementById('dateInterview').addEventListener('change', function () {
            // Obtén el valor de la fecha de entrevista
            var fechaEntrevista = new Date(this.value);

            // Establece la fecha mínima para la fecha del anuncio del candidato (un día después de la fecha de entrevista)
            document.getElementById('dateAnnouncement').min = sumarDias(fechaEntrevista, 1).toISOString().split('T')[0];
        });

        // Función para sumar días a una fecha
        function sumarDias(fecha, dias) {
            var nuevaFecha = new Date(fecha);
            nuevaFecha.setDate(fecha.getDate() + dias);
            return nuevaFecha;
        }
    </script>

    <script>
        function agregarCampo(contenedorId, isFirst, nombreOriginal) {
            // Obtén el contenedor existente
            var contenedorExistente = document.getElementById(contenedorId);

            // Crea un nuevo contenedor div
            var nuevoContenedor = document.createElement('div');
            nuevoContenedor.className = 'input-group w-50';

            // Crea un nuevo elemento de entrada
            var nuevoInput = document.createElement('input');
            nuevoInput.type = 'text';
            nuevoInput.className = 'form-control';
            nuevoInput.placeholder = 'Nuevo ejemplo';
            nuevoInput.required = true;

            // Limitar los caracteres a 150
            nuevoInput.maxLength = 150;

            // Configura el atributo 'name' del nuevo campo
            nuevoInput.name = nombreOriginal;

            // Crea un nuevo botón
            var nuevoBoton = document.createElement('button');
            nuevoBoton.type = 'button';
            nuevoBoton.className = 'btn';

            // Configura el ícono y la función onclick adecuados
            if (isFirst) {
                nuevoBoton.innerHTML = '<i class="fas fa-minus"></i>';
                nuevoBoton.onclick = function () {
                    eliminarCampo(nuevoContenedor);
                };
            } else {
                nuevoBoton.innerHTML = '<i class="fas fa-plus"></i>';
                nuevoBoton.onclick = function () {
                    agregarCampo(contenedorId, false, nombreOriginal);
                };
            }

            // Inserta el nuevo elemento de entrada y el nuevo botón dentro del nuevo contenedor
            nuevoContenedor.appendChild(nuevoInput);
            nuevoContenedor.appendChild(nuevoBoton);

            // Inserta el nuevo contenedor después del contenedor existente
            contenedorExistente.parentNode.insertBefore(nuevoContenedor, contenedorExistente.nextSibling);
        }

        function eliminarCampo(contenedor) {
            // Encuentra el contenedor padre y elimina el contenedor hijo
            contenedor.parentNode.removeChild(contenedor);
        }

        // Funciones específicas para cada tipo de campo dinámico
        function agregarCampoResponsabilidades() {
            agregarCampo('responsabilidadesContainer', true, 'responsibilities[]');
        }

        function agregarCampoBeneficios() {
            agregarCampo('beneficiosContainer', true, 'benefits[]');
        }

        function agregarCampoRequisitos() {
            agregarCampo('requisitosContainer', true, 'requirements[]');
        }

        function agregarCampoevaluators() {
            agregarCampo('evaluatorsContainer', true, 'evaluators[]');
        }

        // Función para enviar el formulario con campos dinámicos
        function enviarFormulario() {
            var inputs = document.querySelectorAll('input[type="text"]:not(.fecha-input)');
            var isValid = true;

            inputs.forEach(function(input) {
                if (input.value.length > 150) {
                    alert('El campo "' + input.placeholder + '" no debe exceder los 150 caracteres.');
                    isValid = false;
                }
            });

            if (isValid) {
                document.getElementById('formularioConvocatoria').submit();
            }
        }
    </script>


</body>
</html>