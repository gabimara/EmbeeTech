<?php

class Ticket
{
    public static function getCategories(): array
    {
        $db = Database::connect();
        $stmt = $db->query('SELECT id, name FROM categories ORDER BY name');
        return $stmt->fetchAll();
    }

    public static function getServiceTypes(): array
    {
        $db = Database::connect();
        $stmt = $db->query('SELECT id, name FROM service_types ORDER BY name');
        return $stmt->fetchAll();
    }

    public static function getAll(int $ownerId = null, bool $includeArchived = false): array
    {
        $db = Database::connect();
        $sql = 'SELECT t.*, u.name AS owner_name, c.name AS category_name, s.name AS service_type_name
                FROM tickets t
                LEFT JOIN users u ON u.id = t.owner_id
                LEFT JOIN categories c ON c.id = t.category_id
                LEFT JOIN service_types s ON s.id = t.service_type_id';

        $conditions = [];
        $params = [];

        if ($ownerId !== null) {
            $conditions[] = 't.owner_id = :owner_id';
            $params['owner_id'] = $ownerId;
        }

        if ($includeArchived) {
            $conditions[] = "TRIM(LOWER(t.status)) = 'cancelado'";
        } else {
            $conditions[] = "TRIM(LOWER(t.status)) != 'cancelado'";
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY t.created_at DESC';
        if (isset($_GET['debug_sql']) && $_GET['debug_sql'] === '1') {
            echo '<pre style="color:#0f0;background:#000;padding:10px;">SQL: ' . htmlspecialchars($sql) . "\nParams: " . htmlspecialchars(json_encode($params, JSON_UNESCAPED_UNICODE)) . '</pre>';
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public static function create(array $data): bool
    {
        $db = Database::connect();
        $stmt = $db->prepare('INSERT INTO tickets (id, title, category_id, service_type_id, details, status, created_at, updated_at, owner_id)
            VALUES (:id, :title, :category_id, :service_type_id, :details, :status, NOW(), NOW(), :owner_id)');

        return $stmt->execute([
            'id' => uniqid('tkt_', true),
            'title' => $data['title'],
            'category_id' => $data['category_id'],
            'service_type_id' => $data['service_type_id'],
            'details' => $data['details'],
            'status' => 'Aberto',
            'owner_id' => $data['owner_id'],
        ]);
    }

    public static function canonicalizeStatus(string $status): string
    {
        $s = mb_strtolower(trim($status));

        if (strpos($s, 'cancel') !== false) {
            return 'Cancelado';
        }
        if (strpos($s, 'andamento') !== false) {
            return 'Em andamento';
        }
        if (strpos($s, 'concl') !== false) {
            return 'Concluído';
        }
        if (strpos($s, 'abert') !== false) {
            return 'Aberto';
        }

        return ucfirst($s);
    }

    public static function updateStatus(string $ticketId, string $status, string $assignedTo): bool
    {
        $status = self::canonicalizeStatus($status);
        $db = Database::connect();
        $stmt = $db->prepare('UPDATE tickets SET status = :status, assigned_to = :assigned_to, updated_at = NOW() WHERE id = :id');
        return $stmt->execute([
            'status' => $status,
            'assigned_to' => $assignedTo,
            'id' => $ticketId,
        ]);
    }

    public static function appendAdminResponse(string $ticketId, string $response): bool
    {
        $db = Database::connect();
        $stmt = $db->prepare('UPDATE tickets SET details = CONCAT(details, :response), updated_at = NOW() WHERE id = :id');
        return $stmt->execute([
            'response' => "\n\nResposta do admin: " . $response,
            'id' => $ticketId,
        ]);
    }

    public static function getById(string $ticketId): ?array
    {
        $db = Database::connect();
        $stmt = $db->prepare('SELECT * FROM tickets WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $ticketId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function appendAdminResponseWithResponder(string $ticketId, string $response, string $responderName): bool
    {
        $db = Database::connect();
        $stmt = $db->prepare('UPDATE tickets SET details = CONCAT(details, :response), updated_at = NOW() WHERE id = :id');
        return $stmt->execute([
            'response' => "\n\nResposta de " . $responderName . ": " . $response,
            'id' => $ticketId,
        ]);
    }

    public static function cleanupOldArchivedTickets(): void
    {
        $db = Database::connect();
        $stmt = $db->prepare('DELETE FROM tickets WHERE status = :status AND updated_at <= DATE_SUB(NOW(), INTERVAL 30 DAY)');
        $stmt->execute(['status' => 'Cancelado']);
    }

    public static function cancelTicket(string $ticketId): bool
    {
        $db = Database::connect();
        $stmt = $db->prepare('UPDATE tickets SET status = :status, updated_at = NOW() WHERE id = :id');
        return $stmt->execute([
            'status' => 'Cancelado',
            'id' => $ticketId,
        ]);
    }

    public static function normalizeExistingStatuses(): void
    {
        $db = Database::connect();

        $mappings = [
            ['like' => '%cancel%', 'status' => 'Cancelado'],
            ['like' => '%conclu%', 'status' => 'Concluído'],
            ['like' => '%andament%', 'status' => 'Em andamento'],
            ['like' => '%abert%', 'status' => 'Aberto'],
        ];

        foreach ($mappings as $map) {
            $stmt = $db->prepare('UPDATE tickets SET status = :status WHERE LOWER(TRIM(status)) LIKE :like');
            $stmt->execute([
                'status' => $map['status'],
                'like' => $map['like'],
            ]);
        }
    }

    public static function countByStatus(string $status, int $ownerId = null): int
    {
        $db = Database::connect();
        $sql = 'SELECT COUNT(*) FROM tickets WHERE status = :status';

        if ($ownerId !== null) {
            $sql .= ' AND owner_id = :owner_id';
        }

        $stmt = $db->prepare($sql);
        $params = ['status' => $status];

        if ($ownerId !== null) {
            $params['owner_id'] = $ownerId;
        }

        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public static function seedDefaults(): void
    {
        $db = Database::connect();

        $countCategories = (int) $db->query('SELECT COUNT(*) FROM categories')->fetchColumn();
        if ($countCategories === 0) {
            $names = ['Hardware', 'Software', 'Rede', 'Segurança', 'Consultoria'];
            $stmt = $db->prepare('INSERT INTO categories (name) VALUES (:name)');
            foreach ($names as $name) {
                $stmt->execute(['name' => $name]);
            }
        }

        $countServiceTypes = (int) $db->query('SELECT COUNT(*) FROM service_types')->fetchColumn();
        if ($countServiceTypes === 0) {
            $names = ['Suporte Remoto', 'Manutenção Local', 'Configuração', 'Diagnóstico', 'Instalação'];
            $stmt = $db->prepare('INSERT INTO service_types (name) VALUES (:name)');
            foreach ($names as $name) {
                $stmt->execute(['name' => $name]);
            }
        }
    }
}
