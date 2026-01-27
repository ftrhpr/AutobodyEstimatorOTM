<?php
/**
 * Admin Model
 */

class Admin
{
    public static function find(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM admins WHERE id = ?", [$id]);
    }

    public static function findByUsername(string $username): ?array
    {
        return Database::fetchOne("SELECT * FROM admins WHERE username = ?", [$username]);
    }

    public static function verifyPassword(array $admin, string $password): bool
    {
        return password_verify($password, $admin['password_hash']);
    }

    public static function updateLastLogin(int $id): void
    {
        Database::update('admins', ['last_login' => date('Y-m-d H:i:s')], 'id = ?', [$id]);
    }

    public static function create(array $data): int
    {
        $data['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
        unset($data['password']);
        $data['created_at'] = date('Y-m-d H:i:s');

        return Database::insert('admins', $data);
    }

    public static function update(int $id, array $data): int
    {
        if (isset($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
            unset($data['password']);
        }

        $data['updated_at'] = date('Y-m-d H:i:s');

        return Database::update('admins', $data, 'id = ?', [$id]);
    }

    public static function all(): array
    {
        return Database::fetchAll("SELECT * FROM admins ORDER BY created_at DESC");
    }

    public static function isSuperAdmin(array $admin): bool
    {
        return $admin['role'] === 'super_admin';
    }
}
