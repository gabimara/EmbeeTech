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
        $assignedTo = trim($_POST['assigned_to'] ?? 'Administrador');

        if (!$ticketId || !$status) {
            $_SESSION['flash_error'] = 'Preencha o status do chamado antes de atualizar.';
            header('Location: index.php?page=admin');
            exit;
        }

        Ticket::updateStatus($ticketId, $status, $assignedTo);
        $_SESSION['flash'] = 'Chamado atualizado com sucesso.';
        header('Location: index.php?page=admin');
        exit;
    }
}
