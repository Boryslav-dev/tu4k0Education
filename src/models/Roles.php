<?php

namespace src\models;

use Framework\MVC\BaseModel;

class Roles extends BaseModel
{
    public string $roleName;

    /**
     * @return string[]
     */
    public function getAttributes(): array
    {
        return ['roleName'];
    }

    /**
     * @return string
     */
    public function getRoleName(): string
    {
        return $this->roleName;
    }

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'Roles';
    }
}
