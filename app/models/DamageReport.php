<?php
/**
 * Damage Report Model
 */

class DamageReport
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_UNDER_REVIEW = 'under_review';
    public const STATUS_ASSESSED = 'assessed';
    public const STATUS_CLOSED = 'closed';

    public const URGENCY_STANDARD = 'standard';
    public const URGENCY_URGENT = 'urgent';

    public static function find(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM damage_reports WHERE id = ?", [$id]);
    }

    public static function findByTicket(string $ticketNumber): ?array
    {
        return Database::fetchOne(
            "SELECT * FROM damage_reports WHERE ticket_number = ?",
            [$ticketNumber]
        );
    }

    public static function findByIdAndUser(int $id, int $userId): ?array
    {
        return Database::fetchOne(
            "SELECT * FROM damage_reports WHERE id = ? AND user_id = ?",
            [$id, $userId]
        );
    }

    public static function findWithDetails(int $id): ?array
    {
        $sql = "SELECT dr.*, v.make, v.model, v.year, v.plate_number,
                       u.name as user_name, u.phone as user_phone
                FROM damage_reports dr
                JOIN vehicles v ON dr.vehicle_id = v.id
                JOIN users u ON dr.user_id = u.id
                WHERE dr.id = ?";

        return Database::fetchOne($sql, [$id]);
    }

    public static function findByUser(int $userId, array $filters = []): array
    {
        $sql = "SELECT dr.*, v.make, v.model, v.year
                FROM damage_reports dr
                JOIN vehicles v ON dr.vehicle_id = v.id
                WHERE dr.user_id = ?";
        $params = [$userId];

        if (!empty($filters['status'])) {
            $sql .= " AND dr.status = ?";
            $params[] = $filters['status'];
        }

        $sql .= " ORDER BY dr.created_at DESC";

        return Database::fetchAll($sql, $params);
    }

    public static function all(array $filters = []): array
    {
        $sql = "SELECT dr.*, v.make, v.model, v.year, v.plate_number,
                       u.name as user_name, u.phone as user_phone
                FROM damage_reports dr
                JOIN vehicles v ON dr.vehicle_id = v.id
                JOIN users u ON dr.user_id = u.id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND dr.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['urgency'])) {
            $sql .= " AND dr.urgency = ?";
            $params[] = $filters['urgency'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (dr.ticket_number LIKE ? OR u.phone LIKE ? OR u.name LIKE ? OR v.make LIKE ? OR v.model LIKE ?)";
            $search = '%' . $filters['search'] . '%';
            $params = array_merge($params, [$search, $search, $search, $search, $search]);
        }

        if (!empty($filters['date_from'])) {
            $sql .= " AND dr.created_at >= ?";
            $params[] = $filters['date_from'] . ' 00:00:00';
        }

        if (!empty($filters['date_to'])) {
            $sql .= " AND dr.created_at <= ?";
            $params[] = $filters['date_to'] . ' 23:59:59';
        }

        $sql .= " ORDER BY dr.created_at DESC";

        if (!empty($filters['limit'])) {
            $sql .= " LIMIT " . (int) $filters['limit'];
            if (!empty($filters['offset'])) {
                $sql .= " OFFSET " . (int) $filters['offset'];
            }
        }

        return Database::fetchAll($sql, $params);
    }

    public static function create(array $data): int
    {
        $data['ticket_number'] = self::generateTicketNumber();
        $data['status'] = self::STATUS_PENDING;
        $data['created_at'] = date('Y-m-d H:i:s');

        return Database::insert('damage_reports', $data);
    }

    public static function update(int $id, array $data): int
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return Database::update('damage_reports', $data, 'id = ?', [$id]);
    }

    public static function updateStatus(int $id, string $status): int
    {
        $data = ['status' => $status, 'updated_at' => date('Y-m-d H:i:s')];

        if ($status === self::STATUS_ASSESSED) {
            $data['assessed_at'] = date('Y-m-d H:i:s');
        }

        return Database::update('damage_reports', $data, 'id = ?', [$id]);
    }

    public static function delete(int $id): int
    {
        return Database::delete('damage_reports', 'id = ?', [$id]);
    }

    public static function count(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) as count FROM damage_reports WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['user_id'])) {
            $sql .= " AND user_id = ?";
            $params[] = $filters['user_id'];
        }

        if (!empty($filters['today'])) {
            $sql .= " AND DATE(created_at) = CURDATE()";
        }

        $result = Database::fetchOne($sql, $params);
        return (int) ($result['count'] ?? 0);
    }

    private static function generateTicketNumber(): string
    {
        $prefix = 'DMG';
        $date = date('Ymd');
        $random = strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
        return $prefix . $date . $random;
    }

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => __('report.status_pending'),
            self::STATUS_UNDER_REVIEW => __('report.status_under_review'),
            self::STATUS_ASSESSED => __('report.status_assessed'),
            self::STATUS_CLOSED => __('report.status_closed'),
        ];
    }

    public static function getStatusBadgeClass(string $status): string
    {
        return match ($status) {
            self::STATUS_PENDING => 'bg-warning',
            self::STATUS_UNDER_REVIEW => 'bg-info',
            self::STATUS_ASSESSED => 'bg-success',
            self::STATUS_CLOSED => 'bg-secondary',
            default => 'bg-secondary',
        };
    }

    public static function getDamageLocations(): array
    {
        return [
            'front' => __('report.location_front'),
            'rear' => __('report.location_rear'),
            'left' => __('report.location_left'),
            'right' => __('report.location_right'),
            'roof' => __('report.location_roof'),
            'hood' => __('report.location_hood'),
            'trunk' => __('report.location_trunk'),
            'windshield' => __('report.location_windshield'),
            'other' => __('report.location_other'),
        ];
    }

    public static function getUrgencyLevels(): array
    {
        return [
            self::URGENCY_STANDARD => __('report.urgency_standard'),
            self::URGENCY_URGENT => __('report.urgency_urgent'),
        ];
    }
}
