<?php
require_once('models/AdminModel.php');

class LoginController {

    public function Index() {
        require_once('views/login/login.php');
    }

    public function Login() {
        if (isset($_POST['Username']) && isset($_POST['Password'])) {
            $Username = $_POST['Username'];
            $Password = $_POST['Password'];

            $result = AdminModel::Login($Username, $Password);

            if ($result['status'] === 'success') {
                $_SESSION['User'] = $result['user']['Username'];
                $_SESSION['Nivel'] = isset($result['user']['Nivel']) ? $result['user']['Nivel'] : 'Administrador';
                
                header("Location: index.php?controller=Obra&action=ListarObra");
                exit();
            } else {
                $errorType = $result['status'];
                require_once('views/login/login.php');
            }
        } else {
            header("Location: index.php");
            exit();
        }
    }

    public function Logout() {
        session_destroy();
        header("Location: index.php");
        exit();
    }
}
?>
