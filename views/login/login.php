<style>
    .login-table {
        background-color: #ffffff;
        color: #212529;
        border: 1px solid #dee2e6;
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }
    .login-table th {
        background-color: #f8f9fa;
        color: #212529;
        padding: 12px;
        border: 1px solid #dee2e6;
        text-align: left;
        font-weight: bold;
        border-bottom: 2px solid #dee2e6;
    }
    .login-table td {
        padding: 15px;
        border: 1px solid #dee2e6;
        vertical-align: middle;
    }
    .login-input {
        background-color: #ffffff;
        border: 1px solid #ced4da;
        color: #495057;
        width: 100%;
        padding: 8px 12px;
        border-radius: 4px;
        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
    .login-input:focus {
        color: #495057;
        background-color: #fff;
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    .btn-light-login {
        background-color: #007bff;
        color: white;
        border: 1px solid #007bff;
        padding: 10px 20px;
        cursor: pointer;
        width: 100%;
        font-weight: bold;
        border-radius: 4px;
    }
    .btn-light-login:hover {
        background-color: #0069d9;
        border-color: #0062cc;
    }
    .alert-light-custom {
        background-color: #fff3cd;
        border: 1px solid #ffeeba;
        color: #856404;
        margin-bottom: 15px;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-6">
        <br>
        <?php if (isset($errorType)): ?>
            <div class="alert alert-light-custom alert-dismissible fade show" role="alert">
                <strong>Mensaje de Advertencia</strong><br>
                <?php 
                if ($errorType === 'invalid_user') {
                    echo "Invalid data: El usuario no existe.";
                } elseif ($errorType === 'incorrect_password') {
                    echo "Incorrect password: La contraseña es incorrecta.";
                }
                ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <form action="?controller=Login&action=Login" method="POST">
            <table class="login-table shadow-sm">
                <thead>
                    <tr>
                        <th colspan="2">Inicio de Sesión</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 30%;"><b>Username:</b></td>
                        <td>
                            <input type="text" name="Username" class="login-input" required placeholder="Ingrese su usuario">
                        </td>
                    </tr>
                    <tr>
                        <td><b>Password:</b></td>
                        <td>
                            <input type="password" name="Password" class="login-input" required placeholder="Ingrese su contraseña">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="background-color: #f8f9fa;">
                            <button type="submit" class="btn-light-login">Conectarse</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>
<p><br></p>
