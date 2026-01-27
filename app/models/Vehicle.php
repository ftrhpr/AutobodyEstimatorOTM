<?php
/**
 * Vehicle Model
 */

class Vehicle
{
    public static function find(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM vehicles WHERE id = ?", [$id]);
    }

    public static function findByUser(int $userId): array
    {
        return Database::fetchAll(
            "SELECT * FROM vehicles WHERE user_id = ? ORDER BY created_at DESC",
            [$userId]
        );
    }

    public static function findByIdAndUser(int $id, int $userId): ?array
    {
        return Database::fetchOne(
            "SELECT * FROM vehicles WHERE id = ? AND user_id = ?",
            [$id, $userId]
        );
    }

    public static function create(array $data): int
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return Database::insert('vehicles', $data);
    }

    public static function update(int $id, array $data): int
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return Database::update('vehicles', $data, 'id = ?', [$id]);
    }

    public static function delete(int $id): int
    {
        return Database::delete('vehicles', 'id = ?', [$id]);
    }

    public static function countByUser(int $userId): int
    {
        return Database::count('vehicles', 'user_id = ?', [$userId]);
    }

    public static function hasReports(int $vehicleId): bool
    {
        return Database::count('damage_reports', 'vehicle_id = ?', [$vehicleId]) > 0;
    }

    public static function getDisplayName(array $vehicle): string
    {
        return sprintf('%d %s %s', $vehicle['year'], $vehicle['make'], $vehicle['model']);
    }

    public static function getMakes(): array
    {
        // Common car manufacturers
        return [
            'Audi', 'BMW', 'Chevrolet', 'Chrysler', 'Citroen', 'Daewoo',
            'Daihatsu', 'Dodge', 'Fiat', 'Ford', 'Honda', 'Hyundai',
            'Infiniti', 'Isuzu', 'Jaguar', 'Jeep', 'Kia', 'Land Rover',
            'Lexus', 'Mazda', 'Mercedes-Benz', 'Mini', 'Mitsubishi',
            'Nissan', 'Opel', 'Peugeot', 'Porsche', 'Renault', 'Saab',
            'Seat', 'Skoda', 'Subaru', 'Suzuki', 'Tesla', 'Toyota',
            'Volkswagen', 'Volvo', 'Other'
        ];
    }

    public static function getYearRange(): array
    {
        $currentYear = (int) date('Y');
        $years = [];
        for ($year = $currentYear + 1; $year >= 1990; $year--) {
            $years[] = $year;
        }
        return $years;
    }
}
