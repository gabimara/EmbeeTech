<?php

class TicketController extends Controller
{
    public function create(): void
    {
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
            $_SESSION['flash_error'] = 'Você precisa estar logado como usuário para abrir um chamado.';
            header('Location: index.php?page=tickets');
            exit;
        }

        $title = trim($_POST['title'] ?? '');
        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $serviceTypeId = (int) ($_POST['service_type_id'] ?? 0);
        $details = trim($_POST['details'] ?? '');

        if (!$title || !$categoryId || !$serviceTypeId || !$details) {
            $_SESSION['flash_error'] = 'Preencha todos os campos para abrir um chamado.';
            header('Location: index.php?page=tickets');
            exit;
        }

        Ticket::create([
            'title' => $title,
            'category_id' => $categoryId,
            'service_type_id' => $serviceTypeId,
            'details' => $details,
            'owner_id' => $_SESSION['user']['id'],
        ]);

        $_SESSION['flash'] = 'Chamado registrado com sucesso!';
        header('Location: index.php?page=tickets');
        exit;
    }

    public function update(): void
    {
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['flash_error'] = 'Você precisa ser administrador para atualizar chamados.';
            header('Location: index.php?page=admin');
            exit;
        }

        $ticketId = trim($_POST['ticket_id'] ?? '');
        $status = trim($_POST['status'] ?? '');
        $assignedTo = isset($_POST['assigned_to']) ? trim($_POST['assigned_to']) : null;
        $adminResponse = trim($_POST['admin_response'] ?? '');
        $responderId = isset($_POST['responder_id']) ? (int) $_POST['responder_id'] : null;

        if (!$ticketId || !$status) {
            $_SESSION['flash_error'] = 'Preencha o status do chamado antes de atualizar.';
            header('Location: index.php?page=admin');
            exit;
        }

        // If assigned_to wasn't provided, preserve existing value
        if ($assignedTo === null) {
            $existing = Ticket::getById($ticketId);
            $assignedTo = $existing['assigned_to'] ?? 'Administrador';
        }

        $ok = Ticket::updateStatus($ticketId, $status, $assignedTo);

        if ($adminResponse) {
            $responderName = 'Admin';
            if ($responderId) {
                $user = User::findById($responderId);
                if ($user) {
                    $responderName = $user['name'];
                }
            } elseif (!empty($_SESSION['user']['name'])) {
                $responderName = $_SESSION['user']['name'];
            }

            Ticket::appendAdminResponseWithResponder($ticketId, $adminResponse, $responderName);
        }

        if (!$ok) {
            $_SESSION['flash_error'] = 'Falha ao atualizar o chamado. Verifique o log.';
            header('Location: index.php?page=admin');
            exit;
        }

        if (trim(strtolower($status)) === 'cancelado') {
            $_SESSION['flash'] = 'Chamado cancelado e arquivado. Chamados cancelados há mais de 30 dias são removidos automaticamente.';
        } else {
            $_SESSION['flash'] = 'Chamado atualizado com sucesso.';
        }

        header('Location: index.php?page=admin');
        exit;
    }

    public function cancel(): void
    {
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['flash_error'] = 'Você precisa ser administrador para cancelar chamados.';
            header('Location: index.php?page=admin');
            exit;
        }

        $ticketId = trim($_POST['ticket_id'] ?? '');
        if (!$ticketId) {
            $_SESSION['flash_error'] = 'Chamado inválido para cancelamento.';
            header('Location: index.php?page=admin');
            exit;
        }

        $ok = Ticket::cancelTicket($ticketId);
        if ($ok) {
            $_SESSION['flash'] = 'Chamado cancelado e arquivado com sucesso.';
        } else {
            $_SESSION['flash_error'] = 'Falha ao cancelar o chamado. Verifique o log.';
        }
        header('Location: index.php?page=admin');
        exit;
    }
}
