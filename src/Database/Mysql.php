<?php

namespace Emanci\MysqlDiff\Database;

use PDO;

class Mysql
{
    /**
     * @var PDO|null
     */
    protected $conn = null;

    /**
     * Mysql construct.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->conn = $this->connect($config);
    }

    /**
     * Establish a database connection.
     *
     * @param array $config
     *
     * @return PDO
     */
    protected function connect(array $config)
    {
        $dsn = sprintf('mysql:host=%s;dbname=%s', $config['host'], $config['database']);

        return $this->createConnection($dsn, $config);
    }

    /**
     * Create a PDO instance via driver invocation.
     *
     * @param array $config
     *
     * @return PDO
     */
    protected function createConnection($dsn, array $config)
    {
        try {
            $conn = new PDO($dsn, $config['user'], $config['password']);
        } catch (PDOException $e) {
            echo 'Connection failed: '.$e->getMessage();

            return;
        }

        return $conn;
    }

    /**
     * Set the connection.
     *
     * @param PDO $conn
     *
     * @return $this
     */
    public function setConnection(PDO $conn)
    {
        $this->conn = $conn;

        return $this;
    }

    /**
     * Returns the connection.
     *
     * @return PDO instance
     */
    public function getConnection()
    {
        return $this->conn;
    }

    /**
     * Returns the all tables.
     *
     * @param string|null $database
     *
     * @return array
     */
    public function tables($database = null)
    {
        $database = $database ? $database : 'DATABASE()';

        $sql = 'SELECT TABLE_NAME AS `name`, 
            ENGINE AS `engine`, 
            AUTO_INCREMENT AS `auto_increment`,
            TABLE_COLLATION AS `collation`, 
            ROW_FORMAT AS `row_format`, 
            TABLE_COMMENT AS `comment` 
            FROM INFORMATION_SCHEMA.TABLES 
            WHERE TABLE_SCHEMA = '.$database;
        $sth = $this->conn->query($sql);

        return $sth->fetchAll();
    }

    /**
     * Returns the all columns of table.
     *
     * @param string      $table
     * @param string|null $database
     *
     * @return array
     */
    public function columns($table, $database = null)
    {
        $database = $database ? $database : 'DATABASE()';

        $sql = 'SELECT COLUMN_NAME AS `field`, 
            COLUMN_TYPE AS `type`, 
            CHARACTER_MAXIMUM_LENGTH AS `length`, 
            IS_NULLABLE AS `null`, 
            COLUMN_KEY AS `key`, 
            COLUMN_DEFAULT AS `default`, 
            EXTRA AS extra, 
            COLUMN_COMMENT AS `comment`, 
            CHARACTER_SET_NAME AS `characterSet`, 
            COLLATION_NAME AS `collation` 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = '.$database.' AND TABLE_NAME = "'.$table.'"';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Returns the all foreign keys of table.
     *
     * @param string      $table
     * @param string|null $database
     *
     * @return array
     */
    public function foreignKeys($table, $database = null)
    {
        $database = null === $database ? 'DATABASE()' : '"'.$database.'"';

        $sql = 'SELECT DISTINCT k.`TABLE_NAME` AS `table_name`, k.`CONSTRAINT_NAME` AS `constraint_name`, k.`COLUMN_NAME` AS `column_name`, k.`REFERENCED_TABLE_NAME` AS `referenced_table_name`, '.
               'k.`REFERENCED_COLUMN_NAME` AS `referenced_column_name` /*!50116 , c.update_rule, c.delete_rule */ '.
               'FROM information_schema.key_column_usage k /*!50116 '.
               'INNER JOIN information_schema.referential_constraints c ON '.
               '  c.constraint_name = k.constraint_name AND '.
               "  c.REFERENCED_TABLE_NAME = '".$table."' */ WHERE k.REFERENCED_TABLE_NAME = '".$table.
               "' AND k.table_schema = ".$database.' /*!50116 AND c.constraint_schema = '.$database.' */  AND k.`REFERENCED_COLUMN_NAME` is not NULL';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Returns the all indexes of table.
     *
     * @param string $table
     *
     * @return array
     */
    public function indexes($table)
    {
        $sql = 'SHOW INDEX FROM '.$table;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Execute an SQL statement and return the number of affected rows.
     *
     * @param string $sql
     *
     * @return int
     */
    public function execSql($sql)
    {
        return $this->conn->exec($sql);
    }

    /**
     * Returns the create table sql string.
     *
     * @param string $table
     *
     * @return string
     */
    public function getCreateTableSql($table)
    {
        $sql = 'SHOW CREATE TABLE `'.$table.'`;';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['Create Table'];
    }
}
