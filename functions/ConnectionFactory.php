<?php

include_once "config/config.php";

class MyPDOStatement extends PDOStatement
{
  protected $_debugValues = null;

  protected function __construct()
  {
    // need this empty construct()!
  }

  public function execute($values=array())
  {
    $this->_debugValues = $values;
    try {
      $t = parent::execute($values);
      // maybe do some logging here?
    } catch (PDOException $e) {
      // maybe do some logging here?
      throw $e;
    }

    return $t;
  }

  public function _debugQuery($replaced=true)
  {
    $q = $this->queryString;

    if (!$replaced) {
      return $q;
    }

    return preg_replace_callback('/:([0-9a-z_]+)/i', array($this, '_debugReplace'), $q);
  }

  protected function _debugReplace($m)
  {
    $v = $this->_debugValues[$m[1]];
    if ($v === null) {
      return "NULL";
    }
    if (!is_numeric($v)) {
      $v = str_replace("'", "''", $v);
    }

    return "'". $v ."'";
  }
}

// have a look at http://www.php.net/manual/en/pdo.constants.php


class ConnectionFactory
{
    private static $factory;


    public static function getFactory() {
        if(!self::$factory)
            self::$factory = new ConnectionFactory();
        return self::$factory;
    }

    public function getConnection() {
        $config = Configuration::$config;
        $dbname = $config['db_name'];
        $host = $config['db_host'];
        $user = $config['db_user'];
        $pass = $config['db_pass'];
        $options = array(
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode=''",
  PDO::ATTR_STATEMENT_CLASS => array('MyPDOStatement', array()),
);

        try {
            $connection = new PDO("mysql:host=$host;dbname=$dbname",$user,$pass,$options);
            $connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        }catch(PDOException $pde) {
            throw $pde;
        }
        return $connection;
    }

    public function close($connection) {
        if(!$connection)
            $connection = null;
    }
    public static function getSecondaryDatabase(){
        $config = Configuration::$config;
        $dbnamesecondary = $config['db_name_secondary'];
        return $dbnamesecondary;
    }
}