<?php
/**
 * Report Photo Model
 */

class ReportPhoto
{
    public static function find(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM report_photos WHERE id = ?", [$id]);
    }

    public static function findByReport(int $reportId): array
    {
        return Database::fetchAll(
            "SELECT * FROM report_photos WHERE report_id = ? ORDER BY uploaded_at ASC",
            [$reportId]
        );
    }

    public static function create(array $data): int
    {
        $data['uploaded_at'] = date('Y-m-d H:i:s');
        return Database::insert('report_photos', $data);
    }

    public static function createMany(int $reportId, array $photos): array
    {
        $ids = [];
        foreach ($photos as $photo) {
            $ids[] = self::create([
                'report_id' => $reportId,
                'file_path' => $photo['path'],
                'original_name' => $photo['original_name'] ?? null,
                'file_size' => $photo['size'] ?? null,
            ]);
        }
        return $ids;
    }

    public static function delete(int $id): int
    {
        $photo = self::find($id);
        if ($photo) {
            FileUpload::delete($photo['file_path']);
        }
        return Database::delete('report_photos', 'id = ?', [$id]);
    }

    public static function deleteByReport(int $reportId): int
    {
        $photos = self::findByReport($reportId);
        foreach ($photos as $photo) {
            FileUpload::delete($photo['file_path']);
        }
        return Database::delete('report_photos', 'report_id = ?', [$reportId]);
    }

    public static function countByReport(int $reportId): int
    {
        return Database::count('report_photos', 'report_id = ?', [$reportId]);
    }

    public static function getMaxPhotosPerReport(): int
    {
        return config('upload.max_files', 10);
    }
}
