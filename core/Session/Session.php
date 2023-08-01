<?php

namespace Framework\Session;

class Session
{
    public static function setName(string $name): void
    {
        $_SESSION['name'] = $name;
    }

    public static function getName(): string
    {
        return $_SESSION['name'];
    }

    public static function setId($id): void
    {
        if (self::sessionExists()) {
            session_id($id);
        }
    }

    public static function getId(): string
    {
        return session_id();
    }

    public static function cookieExists(): bool
    {
        $sessionName = session_name();
        if (isset($_COOKIE[$sessionName]) || isset($_REQUEST[$sessionName])) {
            return true;
        } else {
            return false;
        }
    }

    public static function sessionExists(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            return false;
        } else {
            return true;
        }
    }

    public static function start(): void
    {
        session_start();
    }

    public static function destroy(): void
    {
        if (self::sessionExists() || self::cookieExists()) {
            session_destroy();
        }
    }

    public static function set($key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        return $_SESSION[$key];
    }

    public static function delete($key): void
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function contains($key): bool
    {
        if (self::sessionExists()) {
            foreach ($_SESSION as $sessionKey => $sessionValue) {
                if (str_contains($sessionKey, $key)) {
                    return true;
                }
            }
        }
        return false;
    }
}
