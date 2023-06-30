<?php

declare(strict_types=1);

namespace Framework\MVC;

use Framework\Database\Database;

class BaseModel
{
    protected int $id;

    /**
     * @param array $data
     * @return void
     */
    public function loadData(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public static function getTable(): string
    {
        $class = explode('\\', self::class);

        return end($class);
    }

    /**
     * @return void
     */
    public function save(): void
    {
        if (self::findById($this->id)) {
            $this->update();
        } else {
            $this->insert();
        }
    }

    /**
     * @param int $id
     * @return object
     */
    public static function findById(int $id): object
    {
        return Database::table(self::getTable())->select()->where('id', '=', $id)->setFetchType('Object')->get();
    }

    /**
     * @return array|null
     */
    public static function findAll(): array|null
    {
        return Database::table(self::getTable())->select()->get();
    }

    /**
     * @return int
     */
    public function delete(): int
    {
        return Database::table(self::getTable())->where('id', '=', $this->id)->delete();
    }

    /**
     * @return bool
     */
    public function insert(): bool
    {
        $record = [];
        foreach ($this->getAttributes() as $attribute) {
            $record[$attribute] = $this->$attribute;
        }

        return Database::table(self::getTable())->insert($record);
    }

    /**
     * @return int
     */
    public function update(): int
    {
        $record = [];
        foreach ($this->getAttributes() as $attribute) {
            $record[$attribute] = $this->$attribute;
        }

        return Database::table(self::getTable())->where('id', '=', $this->id)->update($record);
    }
}
