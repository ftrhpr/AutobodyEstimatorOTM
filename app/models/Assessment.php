<?php
/**
 * Assessment Model
 */

class Assessment
{
    public static function find(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM assessments WHERE id = ?", [$id]);
    }

    public static function findByReport(int $reportId): ?array
    {
        $sql = "SELECT a.*, ad.name as admin_name
                FROM assessments a
                JOIN admins ad ON a.admin_id = ad.id
                WHERE a.report_id = ?";

        return Database::fetchOne($sql, [$reportId]);
    }

    public static function findWithItems(int $reportId): ?array
    {
        $assessment = self::findByReport($reportId);

        if ($assessment) {
            $assessment['items'] = AssessmentItem::findByAssessment($assessment['id']);
        }

        return $assessment;
    }

    public static function create(array $data): int
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return Database::insert('assessments', $data);
    }

    public static function update(int $id, array $data): int
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return Database::update('assessments', $data, 'id = ?', [$id]);
    }

    public static function delete(int $id): int
    {
        return Database::delete('assessments', 'id = ?', [$id]);
    }

    public static function createOrUpdate(int $reportId, int $adminId, array $data): int
    {
        $existing = self::findByReport($reportId);

        if ($existing) {
            self::update($existing['id'], $data);
            return $existing['id'];
        }

        return self::create(array_merge($data, [
            'report_id' => $reportId,
            'admin_id' => $adminId,
        ]));
    }

    public static function calculateTotal(int $assessmentId): float
    {
        $sql = "SELECT SUM(cost) as total FROM assessment_items WHERE assessment_id = ?";
        $result = Database::fetchOne($sql, [$assessmentId]);
        return (float) ($result['total'] ?? 0);
    }

    public static function updateTotal(int $assessmentId): void
    {
        $total = self::calculateTotal($assessmentId);
        self::update($assessmentId, ['total_cost' => $total]);
    }

    public static function existsForReport(int $reportId): bool
    {
        return Database::count('assessments', 'report_id = ?', [$reportId]) > 0;
    }
}
