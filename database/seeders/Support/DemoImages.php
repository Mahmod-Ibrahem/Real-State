<?php

namespace Database\Seeders\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

/**
 * Downloads demo photos from Pexels ONCE into storage/app/demo-images (gitignored)
 * and reuses them on every later seed run. Cached files are published to the
 * public disk under Images/Demo and returned as full URLs, matching the format
 * ImagesUtility::storeImage() stores in the database.
 */
class DemoImages
{
    public const PLACEHOLDER = 'assets/images/property/1.jpg';

    private const CACHE_DIR = 'app/demo-images';
    private const PUBLIC_DIR = 'Images/Demo';

    /**
     * @param  callable|null  $log  receives (string $level, string $message)
     * @return string[] public URLs (empty when nothing is cached and Pexels is unavailable)
     */
    public static function pool(string $query, int $count, string $orientation = 'landscape', ?callable $log = null): array
    {
        $log ??= fn (string $level, string $message) => null;
        $slug = Str::slug($query);
        $cacheDir = storage_path(self::CACHE_DIR . '/' . $slug);
        $manifestPath = $cacheDir . '/manifest.json';

        File::ensureDirectoryExists($cacheDir);
        $manifest = File::exists($manifestPath)
            ? (json_decode(File::get($manifestPath), true) ?: [])
            : [];

        // Drop manifest entries whose file disappeared.
        $manifest = array_values(array_filter($manifest, fn ($item) => File::exists($cacheDir . '/' . $item['file'])));

        if (count($manifest) < $count) {
            $manifest = self::download($query, $count, $orientation, $cacheDir, $manifest, $log);
            File::put($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        } else {
            $log('info', "Using cached images for \"{$query}\" (" . count($manifest) . ' files)');
        }

        return self::publish(array_slice($manifest, 0, $count), $slug, $cacheDir);
    }

    public static function placeholder(): string
    {
        return asset(self::PLACEHOLDER);
    }

    private static function download(string $query, int $count, string $orientation, string $cacheDir, array $manifest, callable $log): array
    {
        $key = config('services.pexels.key');
        if (blank($key)) {
            $log('warn', "PEXELS_API_KEY is not set: \"{$query}\" has only " . count($manifest) . " cached images, placeholders will be used.");
            return $manifest;
        }

        $log('info', "Downloading images for \"{$query}\" from Pexels...");

        try {
            $response = Http::withHeaders(['Authorization' => $key])
                ->timeout(30)
                ->retry(2, 500, throw: false)
                ->get('https://api.pexels.com/v1/search', [
                    'query' => $query,
                    'per_page' => min(80, $count),
                    'orientation' => $orientation,
                ]);
        } catch (Throwable $e) {
            $log('warn', "Pexels request failed for \"{$query}\": {$e->getMessage()}");
            return $manifest;
        }

        if ($response->failed()) {
            $log('warn', "Pexels returned HTTP {$response->status()} for \"{$query}\".");
            return $manifest;
        }

        $known = array_column($manifest, 'id');

        foreach ($response->json('photos', []) as $photo) {
            if (count($manifest) >= $count) {
                break;
            }
            if (in_array($photo['id'], $known, true)) {
                continue;
            }

            $file = $photo['id'] . '.jpg';
            try {
                $image = Http::timeout(60)->retry(2, 500)->get($photo['src']['large']);
                File::put($cacheDir . '/' . $file, $image->body());
            } catch (Throwable $e) {
                $log('warn', "Could not download photo {$photo['id']}: {$e->getMessage()}");
                continue;
            }

            $manifest[] = [
                'id' => $photo['id'],
                'file' => $file,
                'photographer' => $photo['photographer'] ?? null,
                'url' => $photo['url'] ?? null,
            ];
        }

        $log('info', 'Cached ' . count($manifest) . " images for \"{$query}\".");

        return $manifest;
    }

    /** Copy cached files to the public disk (only if missing) and return their URLs. */
    private static function publish(array $manifest, string $slug, string $cacheDir): array
    {
        $relativeDir = self::PUBLIC_DIR . '/' . $slug;
        $publicDir = storage_path('app/public/' . $relativeDir);
        File::ensureDirectoryExists($publicDir);

        return array_map(function (array $item) use ($cacheDir, $publicDir, $relativeDir) {
            $target = $publicDir . '/' . $item['file'];
            if (! File::exists($target)) {
                File::copy($cacheDir . '/' . $item['file'], $target);
            }

            return url('storage/' . $relativeDir . '/' . $item['file']);
        }, $manifest);
    }
}
