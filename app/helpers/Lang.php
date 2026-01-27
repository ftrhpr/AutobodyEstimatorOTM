<?php
/**
 * Language/Translation Helper
 * Handles multi-language support (Georgian & English)
 */

class Lang
{
    private static array $translations = [];
    private static string $locale = 'ka'; // Default to Georgian
    private static string $fallback = 'en';

    public static function init(string $locale = 'ka'): void
    {
        self::$locale = $locale;
        self::loadTranslations($locale);

        // Load fallback if not the same as current locale
        if ($locale !== self::$fallback) {
            self::loadTranslations(self::$fallback);
        }
    }

    private static function loadTranslations(string $locale): void
    {
        $file = APP_PATH . '/lang/' . $locale . '.php';

        if (file_exists($file)) {
            self::$translations[$locale] = require $file;
        }
    }

    public static function get(string $key, array $replacements = []): string
    {
        // Try current locale first, then fallback
        $translation = self::findTranslation($key, self::$locale)
            ?? self::findTranslation($key, self::$fallback)
            ?? $key;

        // Replace placeholders
        foreach ($replacements as $placeholder => $value) {
            $translation = str_replace(':' . $placeholder, $value, $translation);
        }

        return $translation;
    }

    private static function findTranslation(string $key, string $locale): ?string
    {
        if (!isset(self::$translations[$locale])) {
            return null;
        }

        $keys = explode('.', $key);
        $value = self::$translations[$locale];

        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return null;
            }
            $value = $value[$k];
        }

        return is_string($value) ? $value : null;
    }

    public static function setLocale(string $locale): void
    {
        self::$locale = $locale;
        self::loadTranslations($locale);
        Session::set('locale', $locale);
    }

    public static function getLocale(): string
    {
        return self::$locale;
    }

    public static function isGeorgian(): bool
    {
        return self::$locale === 'ka';
    }

    public static function all(): array
    {
        return self::$translations[self::$locale] ?? [];
    }

    public static function getAvailableLocales(): array
    {
        return [
            'ka' => 'ქართული',
            'en' => 'English',
        ];
    }
}
