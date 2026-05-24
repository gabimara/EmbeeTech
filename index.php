<?php
session_start();

// Carregar variáveis de ambiente
if (file_exists(__DIR__ . '/.env')) {
    $env = parse_ini_file(__DIR__ . '/.env');
    foreach ($env as $key => $value) {
        putenv("{$key}={$value}");
    }
}

// Carregar autoloader do Composer
require_once __DIR__ . '/vendor/autoload.php';

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
Ticket::cleanupOldArchivedTickets();
// Normalize status values in DB to canonical forms (idempotent)
Ticket::normalizeExistingStatuses();

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

    if ($action === 'register') {
        $authController->register();
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

    if ($action === 'create_admin') {
        $homeController->createAdmin();
        exit;
    }

    if ($action === 'update_user_role') {
        $homeController->updateUserRole();
        exit;
    }

    if ($action === 'update_user_password') {
        $homeController->updateUserPassword();
        exit;
    }

    if ($action === 'delete_user') {
        $homeController->deleteUser();
        exit;
    }

    if ($action === 'change_password') {
        $authController->changePassword();
        exit;
    }

    if ($action === 'send_password_reset') {
        $authController->sendPasswordReset();
        exit;
    }

    if ($action === 'reset_password') {
        $authController->resetPassword();
        exit;
    }
    if ($action === 'update_ticket') {
        $ticketController->update();
        exit;
    }

    if ($action === 'cancel_ticket') {
        $ticketController->cancel();
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
    case 'reset_password':
        $authController->resetPasswordForm($currentUser, $flashMessage, $flashError);
        break;
    default:
        $homeController->index($currentUser, $flashMessage, $flashError);
}
