<?php

class AuthController extends Controller
{
    public function login(): void
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            $_SESSION['flash_error'] = 'Informe email e senha válidos.';
            header('Location: index.php?page=tickets');
            exit;
        }

        $user = User::authenticate($email, $password);

        if (!$user) {
            $_SESSION['flash_error'] = 'Credenciais inválidas. Tente novamente.';
            header('Location: index.php?page=tickets');
            exit;
        }

        $_SESSION['user'] = $user;
        $_SESSION['flash'] = 'Bem-vindo, ' . htmlspecialchars($user['name']) . '!';
        header('Location: index.php');
        exit;
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        session_start();
        $_SESSION['flash'] = 'Você saiu do painel com sucesso.';
        header('Location: index.php');
        exit;
    }
}
