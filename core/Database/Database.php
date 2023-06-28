<?php

declare(strict_types=1);

namespace Framework\Database;

use PDO;
use PDOStatement;
use Symfony\Component\Dotenv\Dotenv;

require_once __DIR__ . '/../../vendor/autoload.php';
class Database
{
    private static PDO $connection;
    private PDOStatement $sqlQuery;
    private int $mode = PDO::FETCH_ASSOC;
    private string $fetchType = 'All';
    private static string $table;
    private string|array $selectParams = '';
    private array $whereCondition = [];
    private string $groupByCondition = '';
    private string $limitCondition = '';
    private array $insertParams = [];
    private array $updateParams = [];
    private bool $deleteCondition = false;
    private string $sqlStatement = '';


    /**
     * @param string $table
     * @return \Framework\Database\Database
     */
    public static function table(string $table): Database
    {
        self::$table = $table;
        return new Database();
    }

    /**
     * @param array|string $columns
     * @return $this
     */
    public function select(array|string $columns = '*'): Database
    {
        if (is_array($columns)) {
            foreach ($columns as $column) {
                if ($column === end($columns)) {
                    $this->selectParams .= $column;
                } else {
                    $this->selectParams .= $column . ', ';
                }
            }
        } else {
            $this->selectParams .= $columns;
        }

        return $this;
    }

    /**
     * @param string $column
     * @param string $operator
     * @param string|int $value
     * @return $this
     */
    public function where(string $column, string $operator, string|int $value): Database
    {
        if ($column && $operator && $value) {
            if (empty($this->whereCondition)) {
                if (is_string($value)) {
                    $this->whereCondition[] = "$column" . "$operator" . "'$value'";
                } else {
                    $this->whereCondition[] = "$column" . "$operator" . $value;
                }
            } else {
                if (is_string($value)) {
                    $this->whereCondition[] = " AND " . "$column" . "$operator" . "'$value'";
                } else {
                    $this->whereCondition[] = " AND " . "$column" . "$operator" . $value;
                }
            }
        }

        return $this;
    }

    /**
     * @param string $column
     * @param string $operator
     * @param string|int $value
     * @return $this
     */
    public function whereOr(string $column, string $operator, string|int $value): Database
    {
        if ($column && $operator && $value) {
            if (is_string($value)) {
                $this->whereCondition[] = " OR " . "$column" . "$operator" . "'$value'";
            } else {
                $this->whereCondition[] = " OR " . "$column" . "$operator" . $value;
            }
        }

        return $this;
    }

    /**
     * @param string $column
     * @param \Framework\Database\Database|array $selectQuery
     * @return $this
     */
    public function whereIn(string $column, Database|array $selectQuery): Database
    {
        if (is_object($selectQuery)) {
            $selectQuery->toSql();
            $selectStatement = $selectQuery->sqlStatement;
        } elseif (is_array($selectQuery)) {
            $selectStatement = implode(", ", array_map(function ($value) {
                if (is_string($value)) {
                    return "'" . $value . "'";
                } else {
                    return $value;
                }
            }, (array)array_values($selectQuery)));
        }
        $this->sqlStatement = "SELECT * from " . self::$table . " WHERE $column IN (" . $selectStatement . ")";

        return $this;
    }

    /**
     * @param array|string $columns
     * @return $this
     */
    public function groupBy(array|string $columns): Database
    {
        if (is_array($columns)) {
            foreach ($columns as $column) {
                if (str_contains($this->selectParams, $column)) {
                    if ($column === end($columns)) {
                        $this->groupByCondition .= $column;
                    } else {
                        $this->groupByCondition .= $column . ', ';
                    }
                }
            }
        } else {
            $this->groupByCondition .= "$columns";
        }

        return $this;
    }

    /**
     * @param int $number
     * @return $this
     */
    public function limit(int $number = 20): Database
    {
        $this->limitCondition = (string)$number;

        return $this;
    }

    /**
     * @return array
     */
    public function get(): mixed
    {
        $this->toSql();
        if ($this->executeQuery()) {
            switch ($this->fetchType) {
                case 'All':
                    $result = $this->sqlQuery->fetchAll($this->mode);
                    break;
                case 'Object':
                    $result = $this->sqlQuery->fetchObject(self::class);
                    break;
            }
        } else {
            $result = null;
        }

        return $result;
    }

    /**
     * @param $fetchType
     * @return $this
     */
    public function setFetchType($fetchType)
    {
        $this->fetchType = $fetchType;

        return $this;
    }

    /**
     * @param $mode
     * @return $this
     */
    public function setFetchMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @param array $statement
     * @return bool
     */
    public function insert(array $statement): bool
    {
        $this->insertParams = $statement;
        $this->toSql();
        if ($this->executeQuery($statement)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param array $statement
     * @return int
     */
    public function update(array $statement): int
    {
        $this->updateParams = $statement;
        $this->toSql();
        $this->executeQuery($statement);

        return $this->sqlQuery->rowCount();
    }

    /**
     * @return int
     */
    public function delete(): int
    {
        $this->deleteCondition = true;
        $this->toSql();
        $this->executeQuery();

        return $this->sqlQuery->rowCount();
    }

    /**
     * @return \PDOStatement
     */
    public function getSqlQuery()
    {
        return $this->sqlQuery;
    }

    /**
     * @return void
     */
    private function connect(): void
    {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../../.env');
        self::$connection = new PDO(
            "mysql:host=" . $_ENV['HOST'] .
            ";dbname=" . $_ENV['DBNAME'],
            $_ENV['NAME'],
            $_ENV['PASSWORD']
        );
    }

    /**
     * @return void
     */
    private function clearQueryParams(): void
    {
        $this->selectParams = '';
        $this->insertParams = [];
        $this->updateParams = [];
        $this->deleteCondition = false;
        $this->whereCondition = [];
        $this->groupByCondition = '';
        $this->limitCondition = '';
    }

    /**
     * @return void
     */
    private function toSql(): void
    {
        if ($this->selectParams) {
            $this->sqlStatement .= sprintf(
                "SELECT %s FROM %s ",
                $this->selectParams,
                self::$table
            );
        } if ($this->insertParams) {
            $this->sqlStatement .= sprintf(
                "INSERT INTO %s (%s) VALUES (%s)",
                self::$table,
                implode(", ", array_keys($this->insertParams)),
                implode(', ', array_map(function ($value) {
                    return ':' . $value;
                },
                array_keys($this->insertParams)))
            );
        } if ($this->updateParams) {
            $this->sqlStatement .= sprintf(
                "UPDATE %s SET %s ",
                self::$table,
                implode(', ', array_map(function ($value) {
                    return $value . '=:' . $value;
                },
                    array_keys($this->updateParams)))
            );
        } if ($this->deleteCondition) {
            $this->sqlStatement .= sprintf(
                "DELETE FROM %s ",
                self::$table
            );
        } if ($this->whereCondition) {
            $this->sqlStatement .= sprintf(
                "WHERE %s ",
                implode('', $this->whereCondition)
            );
        } if ($this->groupByCondition) {
            $this->sqlStatement .= sprintf(
                "GROUP BY %s ",
                $this->groupByCondition
            );
        } if ($this->limitCondition) {
            $this->sqlStatement .= sprintf(
                "LIMIT %s ",
                $this->limitCondition
            );
        }
    }

    /**
     * @param $statement
     * @return bool
     */
    private function executeQuery($statement = null): bool
    {
        $this->connect();
        $this->sqlQuery = self::$connection->prepare($this->sqlStatement);
        if ($statement) {
            foreach (array_keys($statement) as $attribute) {
                $this->sqlQuery->bindParam(":$attribute", $statement[$attribute]);
            }
        }
        $this->clearQueryParams();

        return $this->sqlQuery->execute();
    }
}
