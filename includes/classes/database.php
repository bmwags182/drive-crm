<?php
/*------------------------------
 *  Drive CRM - Database Class
 *  Author: Bret Wagner
 *------------------------------
 */

class Database {
    private $dbhost = DBHOST;
    private $dbame = DBNAME;
    private $dbuser = DBUSER;
    private $dbpass = DBPASS;
    private $dbh;
    private $error;
    private $stmt;


    public function __construct() {
        $dsn = 'mysql:host=' . DBHOST . ';dbname=' . DBNAME;
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
        try {
            $this->dbh = new PDO($dsn, DBUSER, DBPASS, $options);
        } catch (PDOException $e) {
            $this->exception = $e->getMessage();
        }

    }


    public function query($query) {
        $this->stmt = $this->dbh->prepare($query);
    }


    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($param, $value, $type);
    }


    public function execute() {
        return $this->stmt->execute();
    }


    public function result_set() {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function single() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function row_count() {
        return $this->stmt->rowCount();
    }


    public function last_insert_id() {
        return $this->dbh->lastInsertId();
    }


    public function begin_transaction() {
        return $this->dbh->beginTransaction();
    }


    public function end_transaction() {
        return $this->dbh->commit();
    }


    public function debug_dump_params() {
        return $this->stmt->debugDumpParams();
    }


}
