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

    public function register(): void
    {
        $name = trim($_POST['name'] ?? '');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (!$name || !$email || !$password || !$confirmPassword) {
            $_SESSION['flash_error'] = 'Preencha todos os campos para se cadastrar.';
            header('Location: index.php');
            exit;
        }

        if ($password !== $confirmPassword) {
            $_SESSION['flash_error'] = 'As senhas não coincidem.';
            header('Location: index.php');
            exit;
        }

        if (User::findByEmail($email)) {
            $_SESSION['flash_error'] = 'Este e-mail já está cadastrado. Use outro ou faça login.';
            header('Location: index.php');
            exit;
        }

        $userId = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        if (!$userId) {
            $_SESSION['flash_error'] = 'Não foi possível criar sua conta. Tente novamente mais tarde.';
            header('Location: index.php');
            exit;
        }

        $user = User::findByEmail($email);
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];
        $_SESSION['flash'] = 'Cadastro realizado com sucesso! Bem-vindo, ' . htmlspecialchars($name) . '!';
        header('Location: index.php');
        exit;
    }
}
