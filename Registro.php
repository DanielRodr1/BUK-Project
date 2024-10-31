<?php
  session_start();
  $registerError = isset($_SESSION['register_error']) ? $_SESSION['register_error'] : '';
  $formData = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
  unset($_SESSION['register_error']); // Limpiar la sesión después de mostrar los errores
  unset($_SESSION['form_data']); // Limpiar los datos del formulario
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-4C2PxmMj1ZI8Y+9H54uN5IsZ6pJuhtO5YOb7WnHk/KR4J6XGz9znTyy7RzyffsVH" crossorigin="anonymous"/>
    <link rel="stylesheet" href="public/css/register.css"/>
    <title>Registro de usuario</title>
  </head>

  <body>
    <div class="container-fluid">
      <div class="row">
        <!--PRIMER COL EL DE LA IMAGEN-->
        <div class="col-md-7">
          <a class="Empresa" href="Index.html">
            BUK
          </a>
          <img src="img/register.jpg" alt="empresaFondo" class="img-fluid">
        </div>

        <!--SEGUNDO COL EL DEL FORMULARIO-->
        <div class="col-md-5">
          <form action="Clases\Postulante.php" method="POST" onsubmit="return validateForm()">
            <h3 class="title">
              Registro
            </h3>
            
            <div class="form-floating">
              <input type="number" class="form-control" id="document" name="document" placeholder="Ingrese su DNI" autofocus required/>
              <label for="document"><i class="far fa-address-card"></i> Dni</label>
              <div id="dniError" class="error-message"></div>
            </div>

            <div class="form-floating">
              <input type="text" class="form-control" id="name" name="name" placeholder="name" required maxlength="30"/>
              <label for="name"><i class="fas fa-signature"></i> Nombre</label>
            </div>

            <div class="form-floating">
              <input type="text" class="form-control" id="lastName" name="lastName" placeholder="lastName" required maxlength="30"/>
              <label for="lastName"><i class="fas fa-signature"></i> Apellido</label>
            </div>

            <div class="form-floating">
              <input type="number" class="form-control" id="telephone" name="telephone" placeholder="telephone" required/>
              <label for="telephone"><i class="fas fa-mobile-alt"></i> Celular</label>
              <div id="telephoneError" class="error-message"></div>
            </div>

            <div class="form-floating">
              <input type="email" class="form-control" id="email" name="email" placeholder="email" required maxlength="50"/>
              <label for="email"><i class="far fa-envelope"></i> Correo</label>
              <div id="emailError" class="error-message"></div>
            </div>

            <div class="form-floating">
              <input type="password" class="form-control" id="password1" name="password1" placeholder="Password" required/>
              <label for="password1"><i class="fas fa-lock"></i> Contraseña</label>
              <div id="passwordError" class="error-message"></div>
            </div>

            <div class="form-floating">
              <input type="password" class="form-control" id="password2" name="password2" placeholder="Password" required/>
              <label for="password2"><i class="fas fa-lock"></i> Confirmar Contraseña</label>
              <div id="confirmPasswordError" class="error-message"></div>
            </div>

            <button class="btn text-uppercase" type="submit">
              Registrate
            </button>

            <p>
              ¿Estás registrado?
              <a class="phrase" href="Login.php">
                  Inicia Sesión
              </a>
          </p>
        </form>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/761684499b.js" crossorigin="anonymous"></script>
    <script>
      function validateForm() {
        event.preventDefault(); // Evitar el envío del formulario

        // Obtener los valores de los campos
        var dni = document.getElementById('document').value;
        var name = document.getElementById('name').value;
        var lastName = document.getElementById('lastName').value;
        var telephone = document.getElementById('telephone').value;
        var email = document.getElementById('email').value;
        var password1 = document.getElementById('password1').value;
        var password2 = document.getElementById('password2').value;

        // Obtener los elementos de error
        var dniError = document.getElementById('dniError');
        var emailError = document.getElementById('emailError');
        var telephoneError = document.getElementById('telephoneError');
        var passwordError = document.getElementById('passwordError');
        var confirmPasswordError = document.getElementById('confirmPasswordError');

        // Limpiar mensajes de error previos
        dniError.innerHTML = '';
        emailError.innerHTML = '';
        telephoneError.innerHTML = '';
        passwordError.innerHTML = '';
        confirmPasswordError.innerHTML = '';

        // Validar los campos del frontend
        var isValid = true;

        if (dni.length !== 8 || isNaN(dni)) {
          dniError.innerHTML = 'El DNI debe tener exactamente 8 dígitos.';
          dniError.style.color = 'red';
          isValid = false;
        }

        if (telephone.length !== 9 || telephone.charAt(0) !== '9' || isNaN(telephone)) {
          telephoneError.innerHTML = 'El número de celular debe tener exactamente 9 dígitos y comenzar con 9.';
          telephoneError.style.color = 'red';
          isValid = false;
        }

        if (password1.length < 8) {
          passwordError.innerHTML = 'La contraseña debe tener al menos 8 caracteres.';
          passwordError.style.color = 'red';
          isValid = false;
        }

        if (password2 !== password1) {
          confirmPasswordError.innerHTML = 'Las contraseñas no coinciden.';
          confirmPasswordError.style.color = 'red';
          isValid = false;
        }

        // Si la validación del frontend falla, no continuar con el envío
        if (!isValid) {
          return false;
        }

        // Si la validación del frontend es correcta, enviar los datos al backend
        var formData = new FormData();
        formData.append('document', dni);
        formData.append('name', name);
        formData.append('lastName', lastName);
        formData.append('telephone', telephone);
        formData.append('email', email);
        formData.append('password1', password1);

        fetch('Clases/Postulante.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Si el registro es exitoso, redirigir al login
            window.location.href = 'Login.php';
          } else {
            // Si hay un error, mostrar el mensaje correspondiente
            if (data.message.includes('DNI')) {
              dniError.innerHTML = data.message;
              dniError.style.color = 'red';
            } else if (data.message.includes('CORREO')) {
              emailError.innerHTML = data.message;
              emailError.style.color = 'red';
            } else if (data.message.includes('CELULAR')) {
              telephoneError.innerHTML = data.message;
              telephoneError.style.color = 'red';
            }
          }
        })
        .catch(error => console.error('Error:', error));

        return false; // Para evitar el envío normal del formulario
      }
    </script>

  </body>
</html>
