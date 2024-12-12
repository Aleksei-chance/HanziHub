<?php

namespace Framework\Cache;

class Cache
{
    private $cachePath;

    public function __construct()
    {
        $this->cachePath = $_ENV['CACHE_PATH'] ?? __DIR__ . '/../../storage/cache';
        if (!is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0755, true);
        }
    }

    public function set(string $key, $value, int $ttl = 3600): void
    {
        $data = [
            'value' => $value,
            'expires_at' => time() + $ttl,
        ];
        $filePath = $this->getFilePath($key);
        file_put_contents($filePath, serialize($data));
    }

    public function get(string $key)
    {
        $filePath = $this->getFilePath($key);
        if (!file_exists($filePath)) {
            return null;
        }

        $data = unserialize(file_get_contents($filePath));
        if ($data['expires_at'] < time()) {
            $this->delete($key);
            return null;
        }

        return $data['value'];
    }

    public function delete(string $key): void
    {
        $filePath = $this->getFilePath($key);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public function clear(): void
    {
        foreach (glob($this->cachePath . '/*') as $file)
        {
            unlink($file);
        }
    }

    public function getFilePath(string $key): string
    {
        return $this->cachePath . '/' . md5($key) . '.cache';
    }
}
