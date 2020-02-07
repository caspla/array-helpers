<?php
if (!function_exists('array_get')) {
    /**
     * Get element / value of array by key.
     * Dot notation can be used for deep structures.
     * Asterisk can be used as wildcard.
     *
     * @param array $array
     * @param $key
     * @return array|mixed|void
     */
    function array_get(array $array, $key)
    {
        if (strpos($key, '*') !== false) {
            $matches = [];
            foreach (array_flatten($array) as $idx => $value) {
                if (preg_match("/$key/", $idx)) {
                    $matches[$idx] = $value;
                }
            }
            return array_unflatten($matches);
        }
        foreach (explode('.', $key) as $segment) {
            if (!isset($array[$segment])) return;
            $array = $array[$segment];
        }
        return $array;
    }
}

if (!function_exists('array_set')) {
    /**
     * Add element to array. Dot notation can be used.
     *
     * @param array $array
     * @param $key
     * @param $value
     */
    function array_set(array &$array, $key, $value)
    {
        $buffer = &$array;
        $segments = explode('.', $key);
        foreach ($segments as $i => $segment) {
            $buffer = &$buffer[$segment];
            if ($i+1 == count($segments)) $buffer = $value;
        }
    }
}

if (!function_exists('array_omit')) {
    /**
     * Return a copy of array without the given keys.
     *
     * @param array $array
     * @param $keys
     * @param bool $negate
     * @return array
     */
    function array_omit(array &$array, $keys)
    {
        if (!is_array($keys)) $keys = [$keys];
        $data = array_flatten($array);
        foreach (array_keys($data) as $idx) {
            foreach ($keys as $key) {
                $regexp = '/^' . str_replace(['.', '*'], ['\.', '[\w|-]+'], $key) . '$/';
                if (preg_match($regexp, (string)$idx)) {
                    unset($data[$idx]);
                }
            }
        }
        return array_unflatten($data);
    }
}

if (!function_exists('array_keep')) {
    /**
     * Return a copy of array keeping only the given keys.
     *
     * @param array $array
     * @param $keys
     * @return array
     */
    function array_keep(array &$array, $keys)
    {
        if (!is_array($keys)) $keys = [$keys];
        $data = array_flatten($array);
        $keep = [];
        foreach (array_keys($data) as $idx) {
            foreach ($keys as $key) {
                $regexp = '/^' . str_replace(['.', '*'], ['\.', '[\w|-]+'], $key) . '$/';
                if (preg_match($regexp, $idx)) {
                    $keep[] = $idx;
                }
            }
        }
        foreach (array_keys($data) as $idx) {
            if (!in_array($idx, $keep)) {
                unset($data[$idx]);
            }
        }
        return array_unflatten($data);
    }
}

if (!function_exists('array_flatten')) {
    /**
     * Get flat structured version of multidimensional array.
     *
     * @param array $array
     * @param null $prevKey
     * @return array
     */
    function array_flatten(array &$array, $prevKey = null)
    {
        $return = [];
        foreach ($array as $key => $value) {
            $newKey = $key;
            if (!is_null($prevKey)) {
                $newKey = $prevKey . '.' . $key;
            }
            if (is_array($value)) {
                $return += array_flatten($value, $newKey);
            } else {
                $return[$newKey] = $value;
            }
        }
        return $return;
    }
}

if (!function_exists('array_unflatten')) {
    /**
     * Convert flatten array back to its origin structure
     *
     * @param array $array
     * @return array
     */
    function array_unflatten(array &$array)
    {
        $output = [];
        foreach ($array as $key => $value) {
            if (is_string($key)) {
                $segments = explode('.', $key);
            } else {
                $segments[] = $key;
            }
            $nested = &$output;
            while (count($segments) > 1) {
                $nested = &$nested[array_shift($segments)];
                if (!is_array($nested)) $nested = [];
            }
            $nested[array_shift($segments)] = $value;
        }
        return $output;
    }
}