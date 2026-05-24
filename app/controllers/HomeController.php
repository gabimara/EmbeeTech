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

        $this->render('admin', [
            'currentUser' => $currentUser,
            'flashMessage' => $flashMessage,
            'flashError' => $flashError,
            'tickets' => $tickets,
            'categories' => $categories,
            'serviceTypes' => $serviceTypes,
            'admins' => $admins,
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
}
