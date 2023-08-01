<?php

namespace Framework\Response;

class Response
{
    public static function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit();
    }
}
