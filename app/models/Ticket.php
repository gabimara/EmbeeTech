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

    public static function getAll(int $ownerId = null): array
    {
        $db = Database::connect();
        $sql = 'SELECT t.*, u.name AS owner_name, c.name AS category_name, s.name AS service_type_name
                FROM tickets t
                LEFT JOIN users u ON u.id = t.owner_id
                LEFT JOIN categories c ON c.id = t.category_id
                LEFT JOIN service_types s ON s.id = t.service_type_id';

        if ($ownerId !== null) {
            $sql .= ' WHERE t.owner_id = :owner_id';
        }

        $sql .= ' ORDER BY t.created_at DESC';
        $stmt = $db->prepare($sql);

        if ($ownerId !== null) {
            $stmt->execute(['owner_id' => $ownerId]);
        } else {
            $stmt->execute();
        }

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

    public static function updateStatus(string $ticketId, string $status, string $assignedTo): bool
    {
        $db = Database::connect();
        $stmt = $db->prepare('UPDATE tickets SET status = :status, assigned_to = :assigned_to, updated_at = NOW() WHERE id = :id');
        return $stmt->execute([
            'status' => $status,
            'assigned_to' => $assignedTo,
            'id' => $ticketId,
        ]);
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
