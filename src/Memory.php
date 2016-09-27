<?php

namespace Yab\Cerebrum;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

trait Memory
{
    /**
     * Memory duration
     *
     * @var integer
     */
    protected $memoryDuration = 15;

    /**
     * Methods that can be forgetten
     * when the forget method is called
     *
     * @var array
     */
    protected $forgetful = [];

    /**
     * Forget the cached value
     *
     * @param array $args
     *
     * @return mixed
     */
    public function forget($args = [])
    {
        if (! empty($args)) {
            if (is_array($args)) {
                $args = implode('_', $args);
            }
            if (empty($this->forgetful)) {
                $this->forgetful = get_class_methods($this);
            }

            foreach ($this->forgetful as $method) {
                $cacheKey = str_replace('\\', '_', get_class($this).'_'.$method.'_');
                $this->forgetByKey($cacheKey);
                $cacheKey = str_replace('\\', '_', get_class($this).'_'.$method.'_'.$args);
                $this->forgetByKey($cacheKey);
            }
        } else {
            $key = $this->getRememberKey();
            $this->forgetByKey($key);
        }

        return $this;
    }

    /**
     * Remember the value
     *
     * @param  mixed $value
     * @return mixed
     */
    public function remember($value)
    {
        $key = $this->getRememberKey();

        if (Cache::has($key)) {
            $value = Cache::get($key);
        } else {
            $expiresAt = Carbon::now()->addMinutes($this->memoryDuration);

            if (is_callable($value)) {
                $value = $value();
            }

            Cache::put($key, $value, $expiresAt);
        }

        return $value;
    }

    /**
     * Forget something by key.
     *
     * @param  string $key
     * @return bool
     */
    private function forgetByKey($key)
    {
        $result = false;

        if (Cache::has($key)) {
            $result = Cache::forget($key);
        }

        return $result;
    }

    /**
     * get the cache key
     *
     * @return string
     */
    private function getRememberKey()
    {
        $backtrace = debug_backtrace(4)[2];
        $args = implode('_', $backtrace['args']);
        $key = str_replace('\\', '_', get_class($this).'_'.$backtrace['function'].'_'.$args);

        return $key;
    }
}
