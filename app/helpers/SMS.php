<?php
/**
 * SMS Helper Class
 * Sends SMS notifications via various providers
 */

class SMS
{
    private static array $config;

    public static function init(array $config): void
    {
        self::$config = $config;
    }

    public static function send(string $to, string $message): bool
    {
        $to = Validator::sanitizePhone($to);
        $provider = self::$config['provider'] ?? 'twilio';

        // Check rate limiting
        if (!self::checkRateLimit($to)) {
            throw new Exception('SMS rate limit exceeded. Please try again later.');
        }

        switch ($provider) {
            case 'twilio':
                return self::sendViaTwilio($to, $message);
            case 'messagebird':
                return self::sendViaMessagebird($to, $message);
            case 'magti':
                return self::sendViaMagti($to, $message);
            default:
                throw new Exception("Unknown SMS provider: {$provider}");
        }
    }

    public static function sendOTP(string $to, string $otp): bool
    {
        $message = Lang::get('sms.otp_message', ['otp' => $otp]);
        return self::send($to, $message);
    }

    public static function sendReportConfirmation(string $to, string $ticketNumber): bool
    {
        $message = Lang::get('sms.report_submitted', ['ticket' => $ticketNumber]);
        return self::send($to, $message);
    }

    public static function sendAssessmentComplete(string $to, string $ticketNumber, float $totalCost): bool
    {
        $message = Lang::get('sms.assessment_complete', [
            'ticket' => $ticketNumber,
            'total' => number_format($totalCost, 2)
        ]);
        return self::send($to, $message);
    }

    private static function sendViaTwilio(string $to, string $message): bool
    {
        $config = self::$config['twilio'];

        $url = "https://api.twilio.com/2010-04-01/Accounts/{$config['sid']}/Messages.json";

        $data = [
            'To' => $to,
            'From' => $config['from'],
            'Body' => $message,
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD => $config['sid'] . ':' . $config['token'],
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            self::logSMS($to, $message, 'twilio', true);
            return true;
        }

        self::logSMS($to, $message, 'twilio', false, $response);
        return false;
    }

    private static function sendViaMessagebird(string $to, string $message): bool
    {
        $config = self::$config['messagebird'];

        $url = 'https://rest.messagebird.com/messages';

        $data = [
            'recipients' => $to,
            'originator' => $config['originator'],
            'body' => $message,
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: AccessKey ' . $config['api_key'],
                'Content-Type: application/json',
            ],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            self::logSMS($to, $message, 'messagebird', true);
            return true;
        }

        self::logSMS($to, $message, 'messagebird', false, $response);
        return false;
    }

    private static function sendViaMagti(string $to, string $message): bool
    {
        $config = self::$config['magti'];

        // Magti SMS API endpoint (example - adjust based on actual API docs)
        $url = 'https://bi.magtigsm.ge/httpsms/send';

        $data = [
            'username' => $config['username'],
            'password' => $config['password'],
            'client_id' => $config['client_id'],
            'to' => $to,
            'text' => $message,
            'coding' => 'utf-8',
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url . '?' . http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Magti returns "SEND OK" on success
        $success = str_contains($response, 'OK') || $httpCode === 200;

        self::logSMS($to, $message, 'magti', $success, $response);
        return $success;
    }

    private static function checkRateLimit(string $phone): bool
    {
        $maxPerHour = self::$config['rate_limit'] ?? config('security.rate_limit_sms', 5);

        // Check rate limit from database
        $sql = "SELECT COUNT(*) as count FROM sms_logs
                WHERE phone = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)";
        $result = Database::fetchOne($sql, [$phone]);

        return ($result['count'] ?? 0) < $maxPerHour;
    }

    private static function logSMS(string $to, string $message, string $provider, bool $success, ?string $response = null): void
    {
        try {
            Database::insert('sms_logs', [
                'phone' => $to,
                'message' => $message,
                'provider' => $provider,
                'success' => $success ? 1 : 0,
                'response' => $response,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (Exception $e) {
            // Log to file if database logging fails
            error_log("SMS Log Error: " . $e->getMessage());
        }
    }

    public static function generateOTP(int $length = 6): string
    {
        return str_pad((string) random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }
}
