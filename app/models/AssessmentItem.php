<?php
/**
 * Assessment Item Model
 */

class AssessmentItem
{
    public static function find(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM assessment_items WHERE id = ?", [$id]);
    }

    public static function findByAssessment(int $assessmentId): array
    {
        return Database::fetchAll(
            "SELECT * FROM assessment_items WHERE assessment_id = ? ORDER BY created_at ASC",
            [$assessmentId]
        );
    }

    public static function create(array $data): int
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $id = Database::insert('assessment_items', $data);

        // Update assessment total
        Assessment::updateTotal($data['assessment_id']);

        return $id;
    }

    public static function createMany(int $assessmentId, array $items): array
    {
        $ids = [];
        foreach ($items as $item) {
            if (!empty($item['description']) && isset($item['cost'])) {
                $ids[] = Database::insert('assessment_items', [
                    'assessment_id' => $assessmentId,
                    'description' => $item['description'],
                    'cost' => (float) $item['cost'],
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        // Update assessment total
        Assessment::updateTotal($assessmentId);

        return $ids;
    }

    public static function update(int $id, array $data): int
    {
        $item = self::find($id);
        $result = Database::update('assessment_items', $data, 'id = ?', [$id]);

        if ($item) {
            Assessment::updateTotal($item['assessment_id']);
        }

        return $result;
    }

    public static function delete(int $id): int
    {
        $item = self::find($id);
        $result = Database::delete('assessment_items', 'id = ?', [$id]);

        if ($item) {
            Assessment::updateTotal($item['assessment_id']);
        }

        return $result;
    }

    public static function deleteByAssessment(int $assessmentId): int
    {
        return Database::delete('assessment_items', 'assessment_id = ?', [$assessmentId]);
    }

    public static function replaceAll(int $assessmentId, array $items): array
    {
        // Delete existing items
        self::deleteByAssessment($assessmentId);

        // Create new items
        return self::createMany($assessmentId, $items);
    }
}
