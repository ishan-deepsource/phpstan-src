<?php

declare(strict_types=1);

namespace PHPStan\Cache;

class MemoryCacheStorage implements CacheStorage
{
    /** @var array<string, \PHPStan\Cache\CacheItem> */
    private array $storage = [];

    /**
     * @param string $key
     * @param string $variableKey
     * @return mixed|null
     */
    public function load(string $key, string $variableKey)
    {
        if (!isset($this->storage[$key])) {
            return null;
        }

        $item = $this->storage[$key];
        if (!$item->isVariableKeyValid($variableKey)) {
            return null;
        }

        return $item->getData();
    }

    /**
     * @param string $key
     * @param string $variableKey
     * @param mixed $data
     * @return void
     */
    public function save(string $key, string $variableKey, $data): void
    {
        $this->storage[$key] = new CacheItem($variableKey, $data);
    }
}
