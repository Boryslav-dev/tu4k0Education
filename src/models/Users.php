<?php

namespace src\models;

use Framework\Arr\Arr;
use Framework\Database\Database;
use Framework\MVC\BaseModel;

class Users extends BaseModel
{
    public string $name;
    public string $email;
    public string $login;
    public string $password;
    public int $role_id = 1;

    /**
     * @return string[]
     */
    public function getAttributes(): array
    {
        return ['name', 'email', 'login', 'password', 'role_id'];
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUserLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getUserEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'Users';
    }

    /**
     * @param string $login
     * @param string $password
     * @return mixed
     */
    public static function getUser(string $login, string $password): mixed
    {
        return Database::table('Users')
            ->select()->where('login', '=', $login)
            ->where('password', '=', $password)
            ->get();
    }

    /**
     * @param array $user
     * @return bool
     * @throws \Exception
     */
    public static function validateUser(array $user): bool
    {
        if (Arr::firstValue($user)['id']) {
            $user = Database::table(self::getTableName())
                ->select()
                ->where('id', '=', Arr::firstValue($user)['id'])
                ->get();
            if ($user) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
}
