<?php

class Db{
  private $host;
  private $port;
  private $user;
  private $pass;
  private $dbname;

  private $connection;
  private $error;

  private $stmt;

  /**
   * Creates db connection using file with dsn data
   * @param type $confULR 
   * @return type
   */
  public function __construct($confURL){

    $config = parse_ini_file($confURL);

    $this->connector = $config['connector'];
    $this->host = $config['host'];
    $this->port = $config['port'];
    $this->user = $config['username'];
    $this->pass = $config['password'];
    $this->dbname = $config['dbname'];

    // Set DSN
    $dsn = $this->connector . ':host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->dbname;
    // Set options
    $options = array(
      PDO::ATTR_PERSISTENT    => true,
      PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
    );
    // Create a new PDO instanace
    try{
      $this->connection = new PDO($dsn, $this->user, $this->pass, $options);
    }
    // Catch any errors
    catch(PDOException $e){
      $this->error = $e->getMessage();
    }
  }

  /**
   * SQL query peparation
   * @param type $query 
   * @return type
   */
  public function query($query) {
    $this->stmt = $this->connection->prepare($query);
  }

  /**
   * Binding parameters to prepared query
   * @param type $param :name
   * @param type $value "Jhon Smith"
   * @param type|null $type String, int... (int, bool, null and string are automatically detected)
   * @return type
   */
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

  /**
   * Execution of stmt
   * @return type
   */
  public function execute() {
    return $this->stmt->execute();
  }
  
  /**
   * Return all items after execution
   * @return type
   */
  public function resultset() {
    $this->execute();
    return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Return first element after execution
   * @return type
   */
  public function single() {
    $this->execute();
    return $this->stmt->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * Count of items in resultset
   * @return type
   */
  public function rowCount() {
    return $this->stmt->rowCount();
  }

  public function lastInsertedId() {
    return $this->connection->lastInsertedId();
  }

  public function beginTransaction() {
    return $this->connection->beginTransaction();
  }

  public function endTransaction() {
    return $this->connection->commit();
  }

  public function cancelTransaction() {
    return $this->connection->rollBack();
  }

  public function debugDumpParams() {
    return $this->stmt->debugDumpParams();
  }

  public function getLastError() {
    return $this->error;
  }
}