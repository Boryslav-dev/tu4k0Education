<?php

interface IFile {
    public static function writeFile(string $filePath, string|array $message, string $fileMode = 'a+');
    public static function writeJson(string $filePath, string|array $message);
    public static function readFileString(string $filePath);
    public static function readFileArray(string $filePath);
    public static function createFile(string $filePath, string $fileMode);
    public static function deleteFile(string $filePath);
    public static function checkFile(string $filePath);
    public static function getFileName(string $filePath);
    public static function getFileType(string $fileName);
}
