<?php
/**
 * File Upload Helper
 * Handles secure file uploads with validation
 */

class FileUpload
{
    private static array $config;
    private array $errors = [];

    public static function init(array $config): void
    {
        self::$config = $config;
    }

    public function __construct(private array $file)
    {
    }

    public static function handle(array $file): self
    {
        return new self($file);
    }

    public static function handleMultiple(array $files): array
    {
        $uploads = [];

        // Normalize the files array structure
        if (isset($files['name']) && is_array($files['name'])) {
            $fileCount = count($files['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $uploads[] = new self([
                        'name' => $files['name'][$i],
                        'type' => $files['type'][$i],
                        'tmp_name' => $files['tmp_name'][$i],
                        'error' => $files['error'][$i],
                        'size' => $files['size'][$i],
                    ]);
                }
            }
        }

        return $uploads;
    }

    public function validate(): bool
    {
        $this->errors = [];

        // Check for upload errors
        if ($this->file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = $this->getUploadErrorMessage($this->file['error']);
            return false;
        }

        // Validate file size
        $maxSize = self::$config['max_size'] ?? 5 * 1024 * 1024;
        if ($this->file['size'] > $maxSize) {
            $this->errors[] = 'File size exceeds maximum allowed (' . $this->formatSize($maxSize) . ')';
            return false;
        }

        // Validate MIME type
        $allowedTypes = self::$config['allowed_types'] ?? ['image/jpeg', 'image/png', 'image/gif'];
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($this->file['tmp_name']);

        if (!in_array($mimeType, $allowedTypes)) {
            $this->errors[] = 'File type not allowed. Allowed types: ' . implode(', ', $allowedTypes);
            return false;
        }

        // Validate extension
        $allowedExtensions = self::$config['allowed_extensions'] ?? ['jpg', 'jpeg', 'png', 'gif'];
        $extension = strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions)) {
            $this->errors[] = 'File extension not allowed';
            return false;
        }

        // Additional security: Check if it's a real image
        if (str_starts_with($mimeType, 'image/')) {
            $imageInfo = @getimagesize($this->file['tmp_name']);
            if ($imageInfo === false) {
                $this->errors[] = 'Invalid image file';
                return false;
            }
        }

        return true;
    }

    public function store(string $directory, ?string $filename = null): ?string
    {
        if (!$this->validate()) {
            return null;
        }

        // Create directory if it doesn't exist
        $fullPath = PUBLIC_PATH . '/uploads/' . trim($directory, '/');
        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        // Generate unique filename if not provided
        if ($filename === null) {
            $extension = strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));
            $filename = uniqid('img_', true) . '.' . $extension;
        }

        $destination = $fullPath . '/' . $filename;

        // Move uploaded file
        if (!move_uploaded_file($this->file['tmp_name'], $destination)) {
            $this->errors[] = 'Failed to move uploaded file';
            return null;
        }

        // Compress image if it's an image
        $this->compressImage($destination);

        // Return relative path for database storage
        return 'uploads/' . trim($directory, '/') . '/' . $filename;
    }

    private function compressImage(string $path): void
    {
        $imageInfo = getimagesize($path);
        if ($imageInfo === false) {
            return;
        }

        $quality = 85; // Compression quality
        $maxDimension = 1920; // Max width/height

        switch ($imageInfo['mime']) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($path);
                break;
            case 'image/png':
                $image = imagecreatefrompng($path);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($path);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($path);
                break;
            default:
                return;
        }

        if (!$image) {
            return;
        }

        $width = imagesx($image);
        $height = imagesy($image);

        // Resize if too large
        if ($width > $maxDimension || $height > $maxDimension) {
            if ($width > $height) {
                $newWidth = $maxDimension;
                $newHeight = (int) ($height * ($maxDimension / $width));
            } else {
                $newHeight = $maxDimension;
                $newWidth = (int) ($width * ($maxDimension / $height));
            }

            $resized = imagecreatetruecolor($newWidth, $newHeight);

            // Preserve transparency for PNG
            if ($imageInfo['mime'] === 'image/png') {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
            }

            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        // Save compressed image
        switch ($imageInfo['mime']) {
            case 'image/jpeg':
                imagejpeg($image, $path, $quality);
                break;
            case 'image/png':
                imagepng($image, $path, 9);
                break;
            case 'image/gif':
                imagegif($image, $path);
                break;
            case 'image/webp':
                imagewebp($image, $path, $quality);
                break;
        }

        imagedestroy($image);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getFirstError(): ?string
    {
        return $this->errors[0] ?? null;
    }

    private function getUploadErrorMessage(int $errorCode): string
    {
        return match ($errorCode) {
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize directive in php.ini',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE directive in HTML form',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload',
            default => 'Unknown upload error',
        };
    }

    private function formatSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public static function delete(string $path): bool
    {
        $fullPath = PUBLIC_PATH . '/' . ltrim($path, '/');

        if (file_exists($fullPath) && is_file($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }

    public static function deleteDirectory(string $directory): bool
    {
        $fullPath = PUBLIC_PATH . '/uploads/' . trim($directory, '/');

        if (!is_dir($fullPath)) {
            return false;
        }

        $files = array_diff(scandir($fullPath), ['.', '..']);

        foreach ($files as $file) {
            $filePath = $fullPath . '/' . $file;
            if (is_file($filePath)) {
                unlink($filePath);
            }
        }

        return rmdir($fullPath);
    }
}
