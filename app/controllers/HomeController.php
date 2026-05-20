<?php

class HomeController extends Controller
{
    public function index(?array $currentUser, ?string $flashMessage, ?string $flashError): void
    {
        $services = [
            ['icon' => '🛠️', 'title' => 'Suporte Técnico', 'description' => 'Assistência remota e presencial para resolver falhas, erros e performance.'],
            ['icon' => '💾', 'title' => 'Formatação', 'description' => 'Limpeza completa do sistema com backup e recuperação de dados.'],
            ['icon' => '📦', 'title' => 'Instalação de Softwares', 'description' => 'Configuração de pacotes, antivírus, produtividade e ferramentas de TI.'],
            ['icon' => '💡', 'title' => 'Consultorias de TI', 'description' => 'Estratégia digital, infraestrutura e projetos personalizados para sua empresa.'],
        ];

        $categories = Ticket::getCategories();
        $serviceTypes = Ticket::getServiceTypes();
        $tickets = $currentUser ? Ticket::getAll($currentUser['role'] === 'admin' ? null : $currentUser['id']) : [];

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
        $tickets = $currentUser ? Ticket::getAll($currentUser['role'] === 'admin' ? null : $currentUser['id']) : [];

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

        $tickets = Ticket::getAll();
        $categories = Ticket::getCategories();
        $serviceTypes = Ticket::getServiceTypes();

        $this->render('admin', [
            'currentUser' => $currentUser,
            'flashMessage' => $flashMessage,
            'flashError' => $flashError,
            'tickets' => $tickets,
            'categories' => $categories,
            'serviceTypes' => $serviceTypes,
            'page' => 'admin',
        ]);
    }
}
