<?php

class HomeController extends Controller
{
    public function index(?array $currentUser, ?string $flashMessage, ?string $flashError): void
    {
        $services = [
            ['icon' => '🛠️', 'title' => 'Suporte Técnico', 'description' => 'Assistência remota e presencial para resolver falhas, erros e performance.'],
            ['icon' => '💾', 'title' => 'Formatação', 'description' => 'Limpeza completa do sistema com backup e recuperação de dados.'],
            ['icon' => '🖥️', 'title' => 'Manutenção de Computadores', 'description' => 'Serviços completos de hardware, diagnóstico e reparos para computadores pessoais e corporativos.'],
            ['icon' => '📦', 'title' => 'Instalação de Softwares', 'description' => 'Configuração de pacotes, antivírus, produtividade e ferramentas de TI.'],
            ['icon' => '💡', 'title' => 'Consultorias de TI', 'description' => 'Estratégia digital, infraestrutura e projetos personalizados para sua empresa.'],
        ];

        $categories = Ticket::getCategories();
        $serviceTypes = Ticket::getServiceTypes();
        $tickets = $currentUser ? Ticket::getAll($currentUser['role'] === 'admin' ? null : $currentUser['id'], false) : [];

        $this->render('home', [
            'currentUser' => $currentUser,
            'flashMessage' => $flashMessage,
            'flashError' => $flashError,
            'services' => $services,
            'categories' => $categories,
            'serviceTypes' => $serviceTypes,
            'tickets' => $tickets,
            'page' => 'home',
        ]);
    }

    public function tickets(?array $currentUser, ?string $flashMessage, ?string $flashError): void
    {
        $categories = Ticket::getCategories();
        $serviceTypes = Ticket::getServiceTypes();
        $archived = isset($_GET['archived']) && $_GET['archived'] === '1';
        $tickets = $currentUser ? Ticket::getAll($currentUser['role'] === 'admin' ? null : $currentUser['id'], $archived) : [];

        $this->render('home', [
            'currentUser' => $currentUser,
            'flashMessage' => $flashMessage,
            'flashError' => $flashError,
            'services' => [],
            'categories' => $categories,
            'serviceTypes' => $serviceTypes,
            'tickets' => $tickets,
            'page' => 'tickets',
        ]);
    }

    public function admin(?array $currentUser, ?string $flashMessage, ?string $flashError): void
    {
        if (!$currentUser || $currentUser['role'] !== 'admin') {
            header('Location: index.php');
            exit;
        }

        $archived = isset($_GET['archived']) && $_GET['archived'] === '1';
        $tickets = Ticket::getAll(null, $archived);
        $categories = Ticket::getCategories();
        $serviceTypes = Ticket::getServiceTypes();
        $admins = User::getAdmins();
        $users = User::getAll();

        $this->render('admin', [
            'currentUser' => $currentUser,
            'flashMessage' => $flashMessage,
            'flashError' => $flashError,
            'tickets' => $tickets,
            'categories' => $categories,
            'serviceTypes' => $serviceTypes,
            'admins' => $admins,
            'users' => $users,
            'page' => 'admin',
        ]);
    }

    public function createAdmin(): void
    {
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['flash_error'] = 'Você precisa ser administrador para criar outro administrador.';
            header('Location: index.php');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirm = trim($_POST['confirm_password'] ?? '');

        if (!$name || !$email || !$password || !$confirm) {
            $_SESSION['flash_error'] = 'Preencha todos os campos para cadastrar um administrador.';
            header('Location: index.php?page=admin');
            exit;
        }

        if ($password !== $confirm) {
            $_SESSION['flash_error'] = 'As senhas não coincidem.';
            header('Location: index.php?page=admin');
            exit;
        }

        if (User::findByEmail($email)) {
            $_SESSION['flash_error'] = 'Já existe uma conta com esse email.';
            header('Location: index.php?page=admin');
            exit;
        }

        $newId = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => 'admin',
        ]);

        if ($newId) {
            $_SESSION['flash'] = 'Administrador criado com sucesso.';
        } else {
            $_SESSION['flash_error'] = 'Falha ao criar administrador. Verifique o log.';
        }

        header('Location: index.php?page=admin');
        exit;
    }

    public function updateUserRole(): void
    {
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['flash_error'] = 'Você precisa ser administrador para alterar cargos.';
            header('Location: index.php');
            exit;
        }

        $userId = isset($_POST['user_id']) ? (int) $_POST['user_id'] : 0;
        $newRole = trim($_POST['role'] ?? '');

        if (!$userId || !in_array($newRole, ['admin', 'user'], true)) {
            $_SESSION['flash_error'] = 'Escolha um usuário válido e um cargo válido.';
            header('Location: index.php?page=admin');
            exit;
        }

        if ($userId === $_SESSION['user']['id'] && $newRole !== 'admin') {
            $_SESSION['flash_error'] = 'Não é permitido remover o seu próprio acesso de administrador.';
            header('Location: index.php?page=admin');
            exit;
        }

        $success = User::updateRole($userId, $newRole);
        $ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if ($ajax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => (bool) $success,
                'message' => $success ? 'Cargo atualizado com sucesso.' : 'Não foi possível alterar o cargo. Verifique os dados e tente novamente.',
                'role' => $newRole,
                'user_id' => $userId,
            ]);
            exit;
        }

        if ($success) {
            $_SESSION['flash'] = 'Cargo atualizado com sucesso.';
        } else {
            $_SESSION['flash_error'] = 'Não foi possível alterar o cargo. Verifique os dados e tente novamente.';
        }

        header('Location: index.php?page=admin');
        exit;
    }

    public function updateUserPassword(): void
    {
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['flash_error'] = 'Você precisa ser administrador para alterar senhas de usuários.';
            header('Location: index.php');
            exit;
        }

        $userId = isset($_POST['user_id']) ? (int) $_POST['user_id'] : 0;
        $newPassword = trim($_POST['new_password'] ?? '');
        $confirmPassword = trim($_POST['confirm_password'] ?? '');

        if (!$userId || !$newPassword || !$confirmPassword) {
            $_SESSION['flash_error'] = 'Preencha todos os campos para alterar a senha.';
            header('Location: index.php?page=admin');
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['flash_error'] = 'As senhas não coincidem.';
            header('Location: index.php?page=admin');
            exit;
        }

        $user = User::findById($userId);
        if (!$user) {
            $_SESSION['flash_error'] = 'Usuário inválido.';
            header('Location: index.php?page=admin');
            exit;
        }

        if (User::updatePassword($userId, $newPassword)) {
            $_SESSION['flash'] = 'Senha alterada com sucesso para ' . htmlspecialchars($user['email']) . '.';
        } else {
            $_SESSION['flash_error'] = 'Não foi possível alterar a senha. Tente novamente.';
        }

        header('Location: index.php?page=admin');
        exit;
    }

    public function deleteUser(): void
    {
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['flash_error'] = 'Você precisa ser administrador para excluir usuários.';
            header('Location: index.php');
            exit;
        }

        $userId = isset($_POST['user_id']) ? (int) $_POST['user_id'] : 0;

        if (!$userId) {
            $_SESSION['flash_error'] = 'Usuário inválido para exclusão.';
            header('Location: index.php?page=admin');
            exit;
        }

        if ($userId === $_SESSION['user']['id']) {
            $_SESSION['flash_error'] = 'Você não pode excluir sua própria conta enquanto estiver logado.';
            header('Location: index.php?page=admin');
            exit;
        }

        $targetUser = User::findById($userId);
        if (!$targetUser) {
            $_SESSION['flash_error'] = 'Usuário não encontrado.';
            header('Location: index.php?page=admin');
            exit;
        }

        if ($targetUser['role'] === 'admin' && User::countAdmins() <= 1) {
            $_SESSION['flash_error'] = 'Não é possível excluir o último administrador.';
            header('Location: index.php?page=admin');
            exit;
        }

        $deleted = User::delete($userId);
        if ($deleted) {
            $_SESSION['flash'] = 'Usuário excluído com sucesso.';
        } else {
            $_SESSION['flash_error'] = 'Falha ao excluir o usuário. Tente novamente.';
        }

        header('Location: index.php?page=admin');
        exit;
    }
}
