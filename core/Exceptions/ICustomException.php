<?php

interface ICustomException
{
    public function getTime();
    public function __construct(string $message, int $code);
}
