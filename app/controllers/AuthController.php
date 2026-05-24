<?php

use App\Services\MailService;

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

    public function changePassword(): void
    {
        if (empty($_SESSION['user'])) {
            $_SESSION['flash_error'] = 'Você precisa estar logado para alterar sua senha.';
            header('Location: index.php');
            exit;
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (!$currentPassword || !$newPassword || !$confirmPassword) {
            $_SESSION['flash_error'] = 'Preencha todos os campos para alterar a senha.';
            header('Location: index.php');
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['flash_error'] = 'As novas senhas não coincidem.';
            header('Location: index.php');
            exit;
        }

        $user = User::findById((int) $_SESSION['user']['id']);
        if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
            $_SESSION['flash_error'] = 'Senha atual incorreta.';
            header('Location: index.php');
            exit;
        }

        if (User::updatePassword((int) $_SESSION['user']['id'], $newPassword)) {
            $_SESSION['flash'] = 'Senha atualizada com sucesso.';
        } else {
            $_SESSION['flash_error'] = 'Não foi possível atualizar sua senha. Tente novamente.';
        }

        header('Location: index.php');
        exit;
    }

    public function sendPasswordReset(): void
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

        if (!$email) {
            $_SESSION['flash_error'] = 'Informe um email válido para recuperação de senha.';
            header('Location: index.php');
            exit;
        }

        $user = User::findByEmail($email);
        if (!$user) {
            $_SESSION['flash'] = 'Se este email estiver cadastrado, você receberá instruções para redefinir sua senha.';
            header('Location: index.php');
            exit;
        }

        $token = bin2hex(random_bytes(20));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
        User::deletePasswordResetsByEmail($email);
        User::storePasswordReset($email, $token, $expiresAt);

        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $path = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $resetUrl = sprintf('%s://%s%s/index.php?page=reset_password&token=%s', $scheme, $host, $path !== '/' ? $path : '', $token);

        $subject = 'Recuperação de senha - Embee Tech';
        $message = "Olá {$user['name']},\n\nRecebemos um pedido para redefinir sua senha.\nClique no link abaixo para criar uma nova senha:\n\n{$resetUrl}\n\nO link expira em 1 hora. Se você não solicitou essa alteração, ignore esta mensagem.\n\nAtenciosamente,\nEquipe EmbeeTech";

        try {
            $mailService = new MailService();
            if ($mailService->send($email, $subject, $message, $user['name'])) {
                $_SESSION['flash'] = 'Email de recuperação enviado. Verifique sua caixa de entrada.';
            } else {
                $_SESSION['flash_error'] = 'Não foi possível enviar o email. Tente novamente mais tarde.';
            }
        } catch (\Exception $e) {
            error_log('Erro ao enviar email de recuperação: ' . $e->getMessage());
            $_SESSION['flash_error'] = 'Erro ao configurar o serviço de email. Contate o administrador.';
        }

        header('Location: index.php');
        exit;
    }

    public function resetPasswordForm($currentUser = null, $flashMessage = null, $flashError = null): void
    {
        $token = trim($_GET['token'] ?? '');
        if (!$token) {
            $_SESSION['flash_error'] = 'Token de recuperação inválido.';
            header('Location: index.php');
            exit;
        }

        $reset = User::findPasswordReset($token);
        if (!$reset || strtotime($reset['expires_at']) < time()) {
            if ($reset) {
                User::deletePasswordResetByToken($token);
            }
            $_SESSION['flash_error'] = 'Token expirado ou inválido. Solicite um novo link.';
            header('Location: index.php');
            exit;
        }

        $this->render('reset_password', [
            'currentUser' => $currentUser,
            'flashMessage' => $flashMessage,
            'flashError' => $flashError,
            'token' => $token,
            'page' => 'reset_password',
        ]);
    }

    public function resetPassword(): void
    {
        $token = trim($_POST['token'] ?? '');
        $newPassword = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (!$token || !$newPassword || !$confirmPassword) {
            $_SESSION['flash_error'] = 'Preencha todos os campos para redefinir a senha.';
            header('Location: index.php?page=reset_password&token=' . urlencode($token));
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['flash_error'] = 'As senhas não coincidem.';
            header('Location: index.php?page=reset_password&token=' . urlencode($token));
            exit;
        }

        $reset = User::findPasswordReset($token);
        if (!$reset || strtotime($reset['expires_at']) < time()) {
            if ($reset) {
                User::deletePasswordResetByToken($token);
            }
            $_SESSION['flash_error'] = 'Token expirado ou inválido. Solicite um novo link.';
            header('Location: index.php');
            exit;
        }

        $user = User::findByEmail($reset['email']);
        if (!$user) {
            $_SESSION['flash_error'] = 'Usuário não encontrado.';
            header('Location: index.php');
            exit;
        }

        if (User::updatePassword((int) $user['id'], $newPassword)) {
            User::deletePasswordResetByToken($token);
            $_SESSION['flash'] = 'Senha redefinida com sucesso. Faça login com a nova senha.';
        } else {
            $_SESSION['flash_error'] = 'Não foi possível redefinir a senha. Tente novamente.';
        }

        header('Location: index.php');
        exit;
    }
}
