<?php

interface IArr
{
    public static function sortArray(array $array);
    public static function sortIndex(array $array);
    public static function insert(array $array, int|string $index, int|float|string $value);
    public static function append(array $array, mixed $value);
    public static function length(array $array);
    public static function deleteElementByIndex(array $array, int|string $index);
    public static function deleteElementByValue(array $array, mixed $value);
    public static function lastValue(array $array);
    public static function firstValue(array $array);
    public static function slice(array $array, int $startIndex, int $lastIndex);
    public static function sum(array $array);
    public static function reverse(array $array);
}
