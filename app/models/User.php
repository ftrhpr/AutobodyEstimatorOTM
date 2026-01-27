<?php
/**
 * User Model
 */

class User
{
    public static function find(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM users WHERE id = ?", [$id]);
    }

    public static function findByPhone(string $phone): ?array
    {
        $phone = Validator::sanitizePhone($phone);
        return Database::fetchOne("SELECT * FROM users WHERE phone = ?", [$phone]);
    }

    public static function findByEmail(string $email): ?array
    {
        return Database::fetchOne("SELECT * FROM users WHERE email = ?", [$email]);
    }

    public static function create(array $data): int
    {
        $data['phone'] = Validator::sanitizePhone($data['phone']);
        $data['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
        unset($data['password']);

        $data['created_at'] = date('Y-m-d H:i:s');

        return Database::insert('users', $data);
    }

    public static function update(int $id, array $data): int
    {
        if (isset($data['phone'])) {
            $data['phone'] = Validator::sanitizePhone($data['phone']);
        }

        if (isset($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
            unset($data['password']);
        }

        $data['updated_at'] = date('Y-m-d H:i:s');

        return Database::update('users', $data, 'id = ?', [$id]);
    }

    public static function verifyPassword(array $user, string $password): bool
    {
        return password_verify($password, $user['password_hash']);
    }

    public static function activate(int $id): int
    {
        return self::update($id, ['status' => 'active']);
    }

    public static function block(int $id): int
    {
        return self::update($id, ['status' => 'blocked']);
    }

    public static function unblock(int $id): int
    {
        return self::update($id, ['status' => 'active']);
    }

    public static function isActive(array $user): bool
    {
        return $user['status'] === 'active';
    }

    public static function all(array $filters = []): array
    {
        $sql = "SELECT * FROM users WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (phone LIKE ? OR name LIKE ? OR email LIKE ?)";
            $search = '%' . $filters['search'] . '%';
            $params = array_merge($params, [$search, $search, $search]);
        }

        $sql .= " ORDER BY created_at DESC";

        if (!empty($filters['limit'])) {
            $sql .= " LIMIT " . (int) $filters['limit'];
            if (!empty($filters['offset'])) {
                $sql .= " OFFSET " . (int) $filters['offset'];
            }
        }

        return Database::fetchAll($sql, $params);
    }

    public static function count(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) as count FROM users WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }

        $result = Database::fetchOne($sql, $params);
        return (int) ($result['count'] ?? 0);
    }

    public static function phoneExists(string $phone): bool
    {
        $phone = Validator::sanitizePhone($phone);
        return Database::count('users', 'phone = ?', [$phone]) > 0;
    }
}
