<?php

class Mysql
{
    public const DATE_FORMAT = 'Y-m-d H:i:s';
    private static array $instances = [];
    private PDO $conn;
    private string $dbName;

    private function __construct()
    {
        $this->conn = new PDO(
            "mysql:host=".CONNECTION_DB_HOST.";dbname=".CONNECTION_DB_NAME,
            CONNECTION_DB_USER,
            CONNECTION_DB_PASS
        );
        $this->dbName = CONNECTION_DB_NAME;
    }

    public static function getInstance(): Mysql
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        return self::$instances[$cls];
    }

    /**
     * @throws Exception
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    public function selectOne(string $table, string $select = "*", array $params = [])
    {
        $query = "SELECT {$select} FROM {$this->dbName}.{$table}";
        if (!empty($params)) {
            $query = "{$query} WHERE ".implode(' ? AND ', array_keys($params))." ?";
        }
        $sth = $this->conn->prepare($query." LIMIT 1");
        $sth->execute(array_values($params));

        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    public function update(string $table, array $params, array $where = []): bool
    {
        if (empty($params)) {
            return false;
        }

        $query = "UPDATE {$this->dbName}.{$table}";

        $query = "{$query} SET ".implode(' = ?, ', array_keys($params))." = ?";
        $query = "{$query} WHERE ".implode(' ? AND ', array_keys($where))." ?";

        $sth = $this->conn->prepare($query);

        return $sth->execute(array_values(array_merge($params, $where)));
    }

    public function insertOne(string $table, array $rows, array $params): bool
    {
        $values = implode(',', array_fill(0, count($rows), '?'));
        $rows = implode(',', $rows);
        $query = "INSERT INTO {$this->dbName}.{$table} ($rows) VALUES ($values);";

        $sth = $this->conn->prepare($query);

        return $sth->execute($params);
    }

    protected function __clone()
    {
    }
}