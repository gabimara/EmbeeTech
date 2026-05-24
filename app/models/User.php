<?php

class User
{
    public static function findByEmail(string $email): ?array
    {
        $db = Database::connect();
        $stmt = $db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public static function create(array $data): ?int
    {
        $db = Database::connect();
        $stmt = $db->prepare('INSERT INTO users (name, email, password_hash, role, created_at) VALUES (:name, :email, :password_hash, :role, NOW())');
        $role = isset($data['role']) ? $data['role'] : 'user';
        $success = $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => $role,
        ]);

        if (!$success) {
            return null;
        }

        return (int) $db->lastInsertId();
    }

    public static function seedDefaults(): void
    {
        $db = Database::connect();
        $stmt = $db->query('SELECT COUNT(*) FROM users');
        $count = (int) $stmt->fetchColumn();

        if ($count === 0) {
            $passwordAdmin = password_hash('admin123', PASSWORD_DEFAULT);
            $passwordUser = password_hash('user123', PASSWORD_DEFAULT);

            $insert = $db->prepare('INSERT INTO users (name, email, password_hash, role, created_at) VALUES (:name, :email, :password_hash, :role, NOW())');
            $insert->execute(['name' => 'Admin Embee', 'email' => 'admin@embee.com', 'password_hash' => $passwordAdmin, 'role' => 'admin']);
            $insert->execute(['name' => 'Cliente Embee', 'email' => 'user@embee.com', 'password_hash' => $passwordUser, 'role' => 'user']);
        }
    }

    public static function authenticate(string $email, string $password): ?array
    {
        $user = self::findByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            return [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
            ];
        }

        return null;
    }

    public static function findById(int $id): ?array
    {
        $db = Database::connect();
        $stmt = $db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public static function getAdmins(): array
    {
        $db = Database::connect();
        $stmt = $db->prepare('SELECT id, name, email FROM users WHERE role = :role ORDER BY name');
        $stmt->execute(['role' => 'admin']);
        return $stmt->fetchAll();
    }
}
