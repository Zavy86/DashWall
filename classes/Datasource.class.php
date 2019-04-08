<?php
/**
 * Datasource
 *
 * @package DashWall\Classes
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */

/**
 * Datasource class
 */
class Datasource{

 /** Properties */
 private $connection;
 protected $id;
 protected $code;
 protected $description;
 protected $connector;
 protected $hostname;
 protected $database;
 protected $username;
 protected $password;
 protected $tns;
 protected $queries;

 /**
  * Constructor
  */
 public function __construct($datasource){
  // load object
  if(is_numeric($datasource)){$datasource=$GLOBALS['DB']->queryUniqueObject("SELECT * FROM `dashwall__datasources` WHERE `id`='".$datasource."'");}
  if(is_string($datasource)){$datasource=$GLOBALS['DB']->queryUniqueObject("SELECT * FROM `dashwall__datasources` WHERE `code`='".$datasource."'");}
  if(!$datasource->id){return false;}
  // initialize properties
  $this->connection=false;
  $this->id=(int)$datasource->id;
  $this->code=stripslashes($datasource->code);
  $this->description=stripslashes($datasource->description);
  $this->connector=stripslashes($datasource->connector);
  $this->hostname=stripslashes($datasource->hostname);
  $this->database=stripslashes($datasource->database);
  $this->username=stripslashes($datasource->username);
  $this->password=stripslashes($datasource->password);
  $this->tns=stripslashes($datasource->tns);
  $this->queries=stripslashes($datasource->queries);
  // return
  return $this->id;
 }

 /**
  * Get Property
  *
  * @param string $property Property name
  * @return type Property value
  */
 public function __get($property){return $this->$property;}

 /**
  * Query datasource
  *
  * @param string $sql_query
  * @return Query results array
  */
 public function query($sql_query){
  // check for connection
  if($this->connection){
   // get connection
   $pdo_db=$this->connection;
  }else{
   // try to make connection
   try{
    // make pdo dns
    switch($this->connector){
     case "oci":$pdo_dsn="oci:dbname=".$this->tns;break;
     default:$pdo_dsn=$this->connector.":host=".$this->hostname.";dbname=".$this->database;
    }
    // build pdo object
    $pdo_db=new PDO($pdo_dsn,$this->username,$this->password);
    $pdo_db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
   }catch(PDOException $e){echo $e->getMessage();}
   // execute datasource queries
   if($this->queries){
    // cycle all queries
    $queries_array=explode(";",$this->queries);
    foreach($queries_array as $query){
     if(!strlen($query)){continue;}
     try{
      $pdo_statement=$pdo_db->query($query);
     }catch(PDOException $e){
      api_dump($e->getMessage(),"pdo_exception");
      api_dump($sql_query,"sql_query");
     }
    }
   }
   // set connection
   $this->connection=$pdo_db;
  }
  // execution
  try{
   // execute query
   $pdo_statement=$pdo_db->query($sql_query);
   // fetch rows
   $pdo_result=$pdo_statement->fetchAll(PDO::FETCH_OBJ);
   // check results
   if(!is_array($pdo_result)||!count($pdo_result)){$pdo_result=array();}
  }catch(PDOException $e){
   api_dump($e->getMessage(),"pdo_exception");
   api_dump($sql_query,"sql_query");
  }
  // return pdo result array
  return $pdo_result;
 }

}
