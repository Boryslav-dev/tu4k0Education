<?php

declare(strict_types=1);

require_once('IArr.php');

class Arr implements IArr
{
    /**
     * @param array $array
     * @return array
     * @throws \Exception
     */
    public static function sortArray(array $array): array
    {
        if (array_is_list($array)) {
            for ($i = 0; $i < self::length($array); $i++) {
                for ($j = 0; $j < self::length($array); $j++) {
                    if ($array[$i] < $array[$j]) {
                        $temp = $array[$i];
                        $array[$i] = $array[$j];
                        $array[$j] = $temp;
                    }
                }
            }
        } else {
            asort($array, SORT_REGULAR);
        }

        return $array;
    }

    /**
     * @param array $array
     * @return array
     * @throws \Exception
     */
    public static function sortIndex(array $array): array
    {
        $keys = array_keys($array);
        for ($i = 0; $i < self::length($array); $i++) {
            for ($j = 0; $j < self::length($array); $j++) {
                if ($keys[$i] < $keys[$j]) {
                    $tempKey = $keys[$i];
                    $keys[$i] = $keys[$j];
                    $keys[$j] = $tempKey;
                }
            }
        }
        $sortedArray = [];
        foreach ($keys as $key) {
            $sortedArray[$key] = $array[$key];
        }

        return $sortedArray;
    }

    /**
     * @param array $array
     * @param int|string $index
     * @param mixed $value
     * @return array
     * @throws \Exception
     */
    public static function insert(array $array, int|string $index, mixed $value): array
    {
        if (array_key_exists($index, $array)) {
            $array[$index] = $value;
        } else {
            throw new Exception('Index does not exist');
        }

        return $array;
    }

    /**
     * @param array $array
     * @param mixed $value
     * @return array
     */
    public static function append(array $array, mixed $value): array
    {
        if (is_array($value)) {
            foreach ($value as $item) {
                $array = self::append($array, $item);
            }
        } else {
            $lastIndex = self::length($array);
            $array[$lastIndex] = $value;
        }

        return $array;
    }

    /**
     * @param array $array
     * @return int
     */
    public static function length(array $array): int
    {
        $length = 0;
        foreach ($array as $item) {
            $length++;
        }

        return $length;
    }

    /**
     * @param array $array
     * @param int|string $index
     * @return array
     * @throws \Exception
     */
    public static function deleteElementByIndex(array $array, int|string $index): array
    {
        if (array_key_exists($index, $array)) {
            unset($array[$index]);
        } else {
            throw new Exception('Index does not exist');
        }

        return $array;
    }

    /**
     * @param array $array
     * @param mixed $value
     * @return array
     * @throws \Exception
     */
    public static function deleteElementByValue(array $array, mixed $value): array
    {
        if (in_array($value, $array)) {
            foreach ($array as $key) {
                if ($array[$key] === $value) {
                    unset($array[$key]);
                }
            }
        } else {
            throw new Exception('Value does not exist');
        }

        return $array;
    }

    /**
     * @param array $array
     * @return int|float
     * @throws \Exception
     */
    public static function sum(array $array): int|float
    {
        $sum = 0;
        foreach ($array as $value) {
            if (is_string($value)) {
                throw new Exception('Can`t sum array');
            } elseif (is_array($value)) {
                $sum += array_is_list($value) ? self::sum($value) : $value;
            } else {
                $sum += $value;
            }
        }

        return $sum;
    }

    /**
     * @param array $array
     * @return array
     * @throws \Exception
     */
    public static function reverse(array $array): array
    {
        $index = 0;
        $reverseArray = [];
        for ($i = self::length($array) - 1; $i >= 0; $i--) {
            $reverseArray[$index] = $array[$i];
            $index++;
        }

        return $reverseArray;
    }

    /**
     * @param array $array
     * @return mixed
     * @throws \Exception
     */
    public static function lastValue(array $array): mixed
    {
        $index = 0;
        $lastValue = null;
        foreach ($array as $key => $value) {
            if ($index === self::length($array) - 1) {
                $lastValue = $value;
            }
            $index++;
        }

        return $lastValue;
    }

    /**
     * @param array $array
     * @return mixed
     * @throws \Exception
     */
    public static function firstValue(array $array): mixed
    {
        $firstValue = null;
        foreach ($array as $value) {
            $firstValue = $value;
            break;
        }

        return $firstValue;
    }

    /**
     * @param array $array
     * @param int $startIndex
     * @param int $lastIndex
     * @return array
     */
    public static function slice(array $array, int $startIndex, int $lastIndex): array
    {
        $slicedArray = [];
        $values = array_values($array);
        $keys = array_keys($array);
        for ($i = $startIndex; $i < $lastIndex; $i++) {
            $slicedArray[$keys[$i]] = $values[$i];
        }

        return $slicedArray;
    }
}
