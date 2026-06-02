<?php
/**
 * Image Conversion & Optimization Tool for F3 Construction Website
 * Resizes and converts JPG/PNG images to WebP, optimizing fallbacks.
 * Usage: php convert_images.php --dir=<path> [--backup] [--avif]
 */

// Raise memory limit for processing large high-res images (e.g., 7900x5267 pixels)
ini_set('memory_limit', '1024M');
set_time_limit(0);

// Parse CLI options
$options = getopt("", ["dir:", "backup", "avif"]);
$targetDir = isset($options['dir']) ? rtrim($options['dir'], '/\\') : 'media';
$createBackup = isset($options['backup']);
$generateAvif = isset($options['avif']);

if (!is_dir($targetDir)) {
    echo "Error: Directory '$targetDir' does not exist.\n";
    exit(1);
}

$absoluteTargetDir = realpath($targetDir);
$workspaceDir = realpath(__DIR__);
$backupDir = $workspaceDir . DIRECTORY_SEPARATOR . 'media_backup';

echo "=== F3 Construction Image Optimizer ===\n";
echo "Scanning directory: $absoluteTargetDir\n";
echo "Backup enabled: " . ($createBackup ? "Yes (saving to $backupDir)" : "No") . "\n";
echo "Generate AVIF: " . ($generateAvif ? "Yes" : "No") . "\n\n";

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($absoluteTargetDir));
$processedCount = 0;
$totalSavedBytes = 0;

foreach ($iterator as $file) {
    if (!$file->isFile()) {
        continue;
    }

    $filePath = $file->getRealPath();
    $ext = strtolower($file->getExtension());

    // Only process PNG, JPG, JPEG source files
    if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
        continue;
    }

    // Skip files inside the webp/ or avif/ folders to avoid processing generated assets
    if (strpos($filePath, DIRECTORY_SEPARATOR . 'webp' . DIRECTORY_SEPARATOR) !== false ||
        strpos($filePath, DIRECTORY_SEPARATOR . 'avif' . DIRECTORY_SEPARATOR) !== false) {
        continue;
    }

    optimizeImage($filePath, $ext);
}

echo "\n========================================\n";
echo "Optimization Completed!\n";
echo "Total Files Processed: $processedCount\n";
echo "Total Space Saved: " . formatBytes($totalSavedBytes) . "\n";
echo "========================================\n";

/**
 * Optimizes, resizes, and converts a single image.
 */
function optimizeImage($filePath, $ext) {
    global $processedCount, $totalSavedBytes, $createBackup, $backupDir, $workspaceDir, $generateAvif;

    $originalSize = filesize($filePath);
    $filename = basename($filePath);
    
    // Get image dimensions and MIME type
    $info = @getimagesize($filePath);
    if (!$info) {
        echo "[-] Skipping $filename: Cannot read image properties.\n";
        return;
    }

    $origWidth = $info[0];
    $origHeight = $info[1];
    $mimeType = $info['mime'];

    // Determine target max width
    $maxWidth = 1200; // Default generic
    
    $lowerPath = strtolower(str_replace('\\', '/', $filePath));
    
    if (strpos($lowerPath, 'wp-content/uploads') !== false) {
        // Blog/article images
        $maxWidth = 800;
    } elseif (preg_match('/(banner|fondo|slide|inv)/i', $filename)) {
        // High-res banners, backgrounds
        $maxWidth = 1920;
    } elseif (preg_match('/gallery/i', $filename)) {
        // Gallery images
        $maxWidth = 1600;
    }

    echo "[*] Processing $filename ($origWidth x $origHeight, " . formatBytes($originalSize) . ")\n";

    // Load image
    $srcImage = null;
    // Note: siding-1.jpg mime type mismatch check
    if ($mimeType === 'image/png') {
        $srcImage = @imagecreatefrompng($filePath);
    } elseif ($mimeType === 'image/jpeg') {
        $srcImage = @imagecreatefromjpeg($filePath);
    }

    if (!$srcImage) {
        // Attempt load by extension if MIME failed
        if ($ext === 'png') {
            $srcImage = @imagecreatefrompng($filePath);
        } else {
            $srcImage = @imagecreatefromjpeg($filePath);
        }
    }

    if (!$srcImage) {
        echo "  [!] Error: Failed to load image data for $filename.\n";
        return;
    }

    // Determine if resizing is needed
    $targetWidth = $origWidth;
    $targetHeight = $origHeight;

    if ($origWidth > $maxWidth) {
        $ratio = $maxWidth / $origWidth;
        $targetWidth = $maxWidth;
        $targetHeight = (int)($origHeight * $ratio);
    }

    // Create target image canvas
    $dstImage = imagecreatetruecolor($targetWidth, $targetHeight);

    // Keep transparency for PNG files
    if ($mimeType === 'image/png' || $ext === 'png') {
        imagealphablending($dstImage, false);
        imagesavealpha($dstImage, true);
        $transparent = imagecolorallocatealpha($dstImage, 255, 255, 255, 127);
        imagefilledrectangle($dstImage, 0, 0, $targetWidth, $targetHeight, $transparent);
    }

    // Rescale the image
    imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $origWidth, $origHeight);

    // Optional: Backup original file
    if ($createBackup) {
        $relativeDir = str_replace($workspaceDir, '', dirname($filePath));
        $destBackupDir = $backupDir . $relativeDir;
        if (!is_dir($destBackupDir)) {
            mkdir($destBackupDir, 0755, true);
        }
        copy($filePath, $destBackupDir . DIRECTORY_SEPARATOR . $filename);
    }

    // Overwrite the original file with optimized version (resized, compressed fallback)
    // Note: We fix the mimetype mismatch (e.g. siding-1.jpg being PNG) by saving it as a real JPEG
    if ($ext === 'jpg' || $ext === 'jpeg') {
        imagejpeg($dstImage, $filePath, 82);
    } elseif ($ext === 'png') {
        imagepng($dstImage, $filePath, 7); // 0-9 compression level
    }
    
    // Clear status cache to get accurate file size
    clearstatcache();
    $newSize = filesize($filePath);
    $savedBytes = $originalSize - $newSize;
    $totalSavedBytes += $savedBytes;

    echo "  [+] Resized fallback: {$targetWidth}x{$targetHeight}, size: " . formatBytes($newSize) . " (" . number_format(($savedBytes / $originalSize) * 100, 1) . "% saved)\n";

    // Determine target WebP and AVIF output file paths
    // Existing codebase folder convention:
    // media/jpg/ -> media/webp/jpg/
    // media/png/ -> media/webp/png/
    $webpPath = '';
    $avifPath = '';

    if (strpos($lowerPath, '/media/jpg') !== false) {
        $webpDir = str_replace(DIRECTORY_SEPARATOR . 'jpg', DIRECTORY_SEPARATOR . 'webp' . DIRECTORY_SEPARATOR . 'jpg', dirname($filePath));
        $avifDir = str_replace(DIRECTORY_SEPARATOR . 'jpg', DIRECTORY_SEPARATOR . 'avif' . DIRECTORY_SEPARATOR . 'jpg', dirname($filePath));
        $filenameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
        $webpPath = $webpDir . DIRECTORY_SEPARATOR . $filenameWithoutExt . '.webp';
        $avifPath = $avifDir . DIRECTORY_SEPARATOR . $filenameWithoutExt . '.avif';
    } elseif (strpos($lowerPath, '/media/png') !== false) {
        $webpDir = str_replace(DIRECTORY_SEPARATOR . 'png', DIRECTORY_SEPARATOR . 'webp' . DIRECTORY_SEPARATOR . 'png', dirname($filePath));
        $avifDir = str_replace(DIRECTORY_SEPARATOR . 'png', DIRECTORY_SEPARATOR . 'avif' . DIRECTORY_SEPARATOR . 'png', dirname($filePath));
        $filenameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
        $webpPath = $webpDir . DIRECTORY_SEPARATOR . $filenameWithoutExt . '.webp';
        $avifPath = $avifDir . DIRECTORY_SEPARATOR . $filenameWithoutExt . '.avif';
    } else {
        // WordPress or custom folder structure (saves in the same directory)
        $filenameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
        $webpPath = dirname($filePath) . DIRECTORY_SEPARATOR . $filenameWithoutExt . '.webp';
        $avifPath = dirname($filePath) . DIRECTORY_SEPARATOR . $filenameWithoutExt . '.avif';
    }

    // Save WebP version
    if (!empty($webpPath)) {
        if (!is_dir(dirname($webpPath))) {
            mkdir(dirname($webpPath), 0755, true);
        }
        imagewebp($dstImage, $webpPath, 80);
        $webpSize = filesize($webpPath);
        echo "  [+] Saved WebP: " . basename($webpPath) . " (" . formatBytes($webpSize) . ")\n";
    }

    // Save AVIF version if enabled
    if ($generateAvif && !empty($avifPath)) {
        if (!is_dir(dirname($avifPath))) {
            mkdir(dirname($avifPath), 0755, true);
        }
        if (function_exists('imageavif')) {
            imageavif($dstImage, $avifPath, 65);
            $avifSize = filesize($avifPath);
            echo "  [+] Saved AVIF: " . basename($avifPath) . " (" . formatBytes($avifSize) . ")\n";
        } else {
            echo "  [!] Warning: imageavif() not supported on this PHP configuration.\n";
        }
    }

    // Clean up memory
    imagedestroy($srcImage);
    imagedestroy($dstImage);
    
    $processedCount++;
}

/**
 * Format bytes into human readable format.
 */
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}
