<?php

declare(strict_types=1);

require_once('IFile.php');

class File implements IFile
{
    public string $filePath;
    /**
     * @param string $filePath
     * @param string|array $message
     * @param string $fileMode
     * @return void
     * @throws \Exception
     */
    public static function writeFile(string $filePath, string|array $message, string $fileMode = 'a+'): void
    {
        if (is_writable($filePath)) {
            $file = self::openFile($filePath, $fileMode);
            if (is_array($message)) {
                foreach ($message as $value) {
                    if (!fwrite($file, "\n$value")) {
                        throw new FileException('Can`t write array in file', 500);
                    }
                }
            } elseif (!fwrite($file, "\n$message")) {
                throw new FileException('Can`t write string in file', 500);
            }
            self::closeFile($file, $filePath);
        } else {
            throw new FileException('File is not writable', 500);
        }
    }

    /**
     * @param string $filePath
     * @param string|array $message
     * @return void
     * @throws \FileException
     */
    public static function writeJson(string $filePath, string|array $message): void
    {
        if (is_writable($filePath)) {
            try {
                $text = json_encode($message);
                $file = self::openFile($filePath);
                if (!fwrite($file, $text)) {
                    throw new FileException('Can`t write JSON in file', 500);
                }
                self::closeFile($file, $filePath);
            } catch (FileException $fe) {
                print_r($fe->getMessage());
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
        } else {
            throw new FileException('File is not writable', 500);
        }
    }

    /**
     * @param string $filePath
     * @return string|null
     * @throws \FileException
     */
    public static function readFileString(string $filePath): string|null
    {
        $text = null;
        if (is_readable($filePath)) {
            try {
                $file = self::openFile($filePath);
                $text = fread($file, filesize($filePath));
                if (!$text) {
                    throw new FileException('File is empty', 500);
                }
                self::closeFile($file, $filePath);
            } catch (FileException $fe) {
                print_r($fe->getMessage());
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
        } else {
            throw new FileException("File is not readable", 500);
        }

        return $text;
    }


    /**
     * @param string $filePath
     * @return bool|array
     * @throws \Exception
     */
    public static function readFileArray(string $filePath): bool|array
    {
        if (is_readable($filePath)) {
            $file = self::openFile($filePath);
            if ($file) {
                $text = fread($file, filesize($filePath));
                if ($text) {
                    if (self::isJson($text)) {
                        $array = json_decode($text, true);
                    } else {
                        $array = file($filePath);
                    }
                } else {
                    throw new FileException("File is empty", 500);
                }
            } else {
                throw new FileException("Can`t open file", 500);
            }
        } else {
            throw new FileException("File not exist", 500);
        }
        self::closeFile($file, $filePath);

        return $array;
    }

    /**
     * @param string $filePath
     * @param string $fileMode
     * @return void
     * @throws \FileException
     */
    public static function createFile(string $filePath, string $fileMode): void
    {
        if (file_exists($filePath)) {
            throw new FileException('File already created', 500);
        } else {
            $file = fopen($filePath, $fileMode);
            if (!$file) {
                throw new FileException('Can`t create file', 500);
            }
            self::closeFile($file, $filePath);
        }
    }

    /**
     * @param string $filePath
     * @return void
     * @throws \FileException
     */
    public static function deleteFile(string $filePath): void
    {
        if (!unlink($filePath)) {
            throw new FileException('Can`t delete file', 500);
        }
    }

    /**
     * @param string $filePath
     * @return bool
     */
    public static function checkFile(string $filePath): bool
    {
        if (!is_file($filePath)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param string $filePath
     * @return string
     */
    public static function getFileName(string $filePath): string
    {
        $filePathChunks = explode('/', $filePath);

        return end($filePathChunks);
    }

    /**
     * @param string $fileName
     * @return string
     */
    public static function getFileType(string $fileName): string
    {
        $fileNameChunks = explode('.', $fileName);

        return end($fileNameChunks);
    }

    /**
     * @param string $message
     * @return mixed
     */
    private static function isJson(string $message): mixed
    {
        $messageJson = json_decode($message);
        if (json_last_error() == JSON_ERROR_NONE) {
            return $messageJson;
        }

        return false;
    }

    /**
     * @param string $filePath
     * @param string $fileMode
     * @return resource
     * @throws \FileException
     */
    private static function openFile(string $filePath, string $fileMode = 'a+')
    {
        $file = fopen($filePath, $fileMode);
        if (!$file) {
            throw new FileException('Can`t open file', 500);
        }

        return $file;
    }

    /**
     * @param mixed $file
     * @param string $filePath
     * @return void
     * @throws \FileException
     */
    private static function closeFile(mixed $file, string $filePath): void
    {
        if (!fclose($file)) {
            throw new FileException("Can`t close file", 500);
        }
    }
}
