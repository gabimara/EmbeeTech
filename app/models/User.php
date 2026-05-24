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

    public static function getAll(): array
    {
        $db = Database::connect();
        $stmt = $db->query('SELECT id, name, email, role FROM users ORDER BY role DESC, name');
        return $stmt->fetchAll();
    }

    public static function updateRole(int $id, string $role): bool
    {
        if (!in_array($role, ['admin', 'user'], true)) {
            return false;
        }

        $db = Database::connect();
        $stmt = $db->prepare('UPDATE users SET role = :role WHERE id = :id');
        return $stmt->execute(['role' => $role, 'id' => $id]);
    }

    public static function delete(int $id): bool
    {
        $db = Database::connect();
        $stmt = $db->prepare('DELETE FROM users WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public static function countAdmins(): int
    {
        $db = Database::connect();
        $stmt = $db->prepare('SELECT COUNT(*) FROM users WHERE role = :role');
        $stmt->execute(['role' => 'admin']);
        return (int) $stmt->fetchColumn();
    }

    public static function updatePassword(int $id, string $password): bool
    {
        $db = Database::connect();
        $stmt = $db->prepare('UPDATE users SET password_hash = :password_hash WHERE id = :id');
        return $stmt->execute([
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'id' => $id,
        ]);
    }

    private static function ensurePasswordResetTableExists(): void
    {
        $db = Database::connect();
        $db->exec(
            'CREATE TABLE IF NOT EXISTS password_resets (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) NOT NULL,
                token VARCHAR(255) NOT NULL,
                expires_at DATETIME NOT NULL,
                created_at DATETIME NOT NULL,
                INDEX (token),
                INDEX (email)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );
    }

    public static function storePasswordReset(string $email, string $token, string $expiresAt): bool
    {
        self::ensurePasswordResetTableExists();
        $db = Database::connect();
        $stmt = $db->prepare('INSERT INTO password_resets (email, token, expires_at, created_at) VALUES (:email, :token, :expires_at, NOW())');
        return $stmt->execute([
            'email' => $email,
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);
    }

    public static function findPasswordReset(string $token): ?array
    {
        self::ensurePasswordResetTableExists();
        $db = Database::connect();
        $stmt = $db->prepare('SELECT * FROM password_resets WHERE token = :token LIMIT 1');
        $stmt->execute(['token' => $token]);
        $reset = $stmt->fetch();

        return $reset ?: null;
    }

    public static function deletePasswordResetByToken(string $token): bool
    {
        self::ensurePasswordResetTableExists();
        $db = Database::connect();
        $stmt = $db->prepare('DELETE FROM password_resets WHERE token = :token');
        return $stmt->execute(['token' => $token]);
    }

    public static function deletePasswordResetsByEmail(string $email): bool
    {
        self::ensurePasswordResetTableExists();
        $db = Database::connect();
        $stmt = $db->prepare('DELETE FROM password_resets WHERE email = :email');
        return $stmt->execute(['email' => $email]);
    }
}
