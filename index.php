<?php
session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/core/Controller.php';
require_once __DIR__ . '/app/models/User.php';
require_once __DIR__ . '/app/models/Ticket.php';
require_once __DIR__ . '/app/controllers/HomeController.php';
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/controllers/TicketController.php';

$currentUser = $_SESSION['user'] ?? null;
$flashMessage = $_SESSION['flash'] ?? null;
$flashError = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash'], $_SESSION['flash_error']);

User::seedDefaults();
Ticket::seedDefaults();

$page = $_GET['page'] ?? 'home';
$action = $_POST['action'] ?? null;

$homeController = new HomeController();
$authController = new AuthController();
$ticketController = new TicketController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'login') {
        $authController->login();
        exit;
    }

    if ($action === 'logout') {
        $authController->logout();
        exit;
    }

    if ($action === 'create_ticket') {
        $ticketController->create();
        exit;
    }

    if ($action === 'update_ticket') {
        $ticketController->update();
        exit;
    }
}

switch ($page) {
    case 'tickets':
        $homeController->tickets($currentUser, $flashMessage, $flashError);
        break;
    case 'admin':
        $homeController->admin($currentUser, $flashMessage, $flashError);
        break;
    default:
        $homeController->index($currentUser, $flashMessage, $flashError);
}
