<?php

declare(strict_types=1);

require_once('ICustomException.php');

abstract class CustomException extends Exception implements ICustomException
{
    /**
     * @var string
     */
    protected string $time;

    /**
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message, int $code)
    {
        parent::__construct($message, $code);
        $this->time = date("H:i:s");
    }

    /**
     * @return string
     */
    public function getTime(): string
    {
        return $this->time;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "Status code: $this->code" .
            "\nTime: [$this->time]" .
            "\nException: " . get_class($this) .
            "\nMessage: '{$this->message}'" .
            "\nIn: {$this->file}" .
            "\nLine: {$this->line}\n";
    }
}
