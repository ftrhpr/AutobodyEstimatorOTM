<?php
/**
 * OTP Code Model
 */

class OtpCode
{
    public const TYPE_REGISTRATION = 'registration';
    public const TYPE_PASSWORD_RESET = 'password_reset';
    public const TYPE_VERIFICATION = 'verification';

    public static function generate(string $phone, string $type = self::TYPE_REGISTRATION): string
    {
        $phone = Validator::sanitizePhone($phone);

        // Invalidate previous codes
        self::invalidatePrevious($phone, $type);

        // Generate new code
        $code = SMS::generateOTP(config('security.otp_length', 6));
        $expiresAt = date('Y-m-d H:i:s', time() + config('security.otp_expiry', 300));

        Database::insert('otp_codes', [
            'phone' => $phone,
            'code' => $code,
            'type' => $type,
            'expires_at' => $expiresAt,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $code;
    }

    public static function verify(string $phone, string $code, string $type = self::TYPE_REGISTRATION): bool
    {
        $phone = Validator::sanitizePhone($phone);

        $sql = "SELECT * FROM otp_codes
                WHERE phone = ? AND code = ? AND type = ?
                AND expires_at > NOW() AND used_at IS NULL
                ORDER BY created_at DESC LIMIT 1";

        $otp = Database::fetchOne($sql, [$phone, $code, $type]);

        if (!$otp) {
            // Increment attempts on failed verification
            self::incrementAttempts($phone, $type);
            return false;
        }

        // Check max attempts
        if ($otp['attempts'] >= config('security.max_otp_attempts', 3)) {
            return false;
        }

        // Mark as used
        Database::update('otp_codes', ['used_at' => date('Y-m-d H:i:s')], 'id = ?', [$otp['id']]);

        return true;
    }

    public static function isValid(string $phone, string $type = self::TYPE_REGISTRATION): bool
    {
        $phone = Validator::sanitizePhone($phone);

        $sql = "SELECT * FROM otp_codes
                WHERE phone = ? AND type = ?
                AND expires_at > NOW() AND used_at IS NULL
                ORDER BY created_at DESC LIMIT 1";

        $otp = Database::fetchOne($sql, [$phone, $type]);

        return $otp !== null && $otp['attempts'] < config('security.max_otp_attempts', 3);
    }

    private static function invalidatePrevious(string $phone, string $type): void
    {
        Database::update(
            'otp_codes',
            ['used_at' => date('Y-m-d H:i:s')],
            'phone = ? AND type = ? AND used_at IS NULL',
            [$phone, $type]
        );
    }

    private static function incrementAttempts(string $phone, string $type): void
    {
        $sql = "UPDATE otp_codes SET attempts = attempts + 1
                WHERE phone = ? AND type = ? AND used_at IS NULL
                ORDER BY created_at DESC LIMIT 1";
        Database::query($sql, [$phone, $type]);
    }

    public static function canResend(string $phone, string $type = self::TYPE_REGISTRATION): bool
    {
        $phone = Validator::sanitizePhone($phone);

        // Check if there's a recent code (less than 60 seconds old)
        $sql = "SELECT * FROM otp_codes
                WHERE phone = ? AND type = ?
                AND created_at > DATE_SUB(NOW(), INTERVAL 60 SECOND)
                ORDER BY created_at DESC LIMIT 1";

        return Database::fetchOne($sql, [$phone, $type]) === null;
    }

    public static function cleanup(): int
    {
        // Delete expired codes older than 24 hours
        return Database::delete(
            'otp_codes',
            'expires_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)'
        );
    }
}
