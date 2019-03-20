<?php
/**
 * Database
 *
 * @package DashWall\Classes
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */

/** @todo check and fixs */

/**
 * Database class
 */
class Database{

 /** Properties */
 private $connection;

 public $query_counter;
 public $cache_query_counter;
 public $logs_array;

 private $cache_query_array;
 private $cache_query_array_results;


 /** @todo commentare bene*/

 public function __construct($configuration){
  try{
   $this->connection=new PDO($configuration->db_type.":host=".$configuration->db_host.";port=".$configuration->db_port.";dbname=".$configuration->db_name.";charset=utf8",$configuration->db_user,$configuration->db_pass);
   $this->connection->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND,"SET NAMES utf8");
   $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
   $this->connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
   $this->query_counter=0;
   $this->cache_query_counter=0;
   $this->cache_query_array=array();
   $this->cache_query_array_results=array();
   $this->logs_array=array();
   $this->logs_array[]=array("log","PDO connection: connected to ".$configuration->db_name." ".strtoupper($configuration->db_type)." database on server ".$configuration->db_host);
  }catch(PDOException $e){
   $this->logs_array[]=array("error","PDO connection: ".$e->getMessage());
   die("PDO connection: ".$e->getMessage());
  }
 }


 public function __call($method,$args){
  $this->logs_array[]=array("warn","Method ".$method."(".implode(",",$args).") was not found in ".get_class($this)." class");
 }


 private function addQueryToCache($sql,$result){
  if(substr(strtoupper($sql),0,6)=="SELECT"){
   $this->cache_query_array[$this->query_counter]=$sql;
   $this->cache_query_array_results[$this->query_counter]=$result;
   return true;
  }else{
   return false;
  }
 }


 private function getQueryFromCache($sql){
  $cached_query_key=array_search($sql,$this->cache_query_array,true);
  if($cached_query_key){
   $this->logs_array[]=array("log","PDO queryUniqueObject result from cache id #".$cached_query_key."\n");
   $return=$this->cache_query_array_results[$cached_query_key];
   $this->cache_query_counter++;
   return $return;
  }else{
   return false;
  }
 }


 public function affectedRows(){
 }


 public function queryExecute($sql){
  $this->logs_array[]=array("log","PDO queryExecute: ".$sql);
  try{
   $query=$this->connection->prepare($sql);
   $query->execute();
   $return=$query->rowCount();
   //$return=$query->execute();
  }catch(PDOException $e){
   $this->logs_array[]=array("error","PDO queryExecute: ".$e->getMessage());
   $return=false;
  }
  $this->query_counter++;
  return $return;
 }


 public function queryObjects($sql,$cache=true){
  $this->logs_array[]=array("log","PDO queryObjects: ".$sql);
  // check for cache
  if($cache){
   $return=$this->getQueryFromCache($sql);
   if($return!==false){return $return;}
  }
  // execute query
  try{
   $results=$this->connection->query($sql);
   $return=$results->fetchAll(PDO::FETCH_OBJ);
   if(DEBUG){$this->logs_array[]=array("log","PDO queryObjects results:\n".var_export($return,true));}
  }catch(PDOException $e){
   $this->logs_array[]=array("warn","PDO queryObjects: ".$e->getMessage());
   $return=false;
  }
  if(!is_array($return)){$return=array();}
  $this->query_counter++;
  if($cache){$this->addQueryToCache($sql,$return);}
  return $return;
 }


 public function queryUniqueObject($sql,$cache=true){
  $sql.=" LIMIT 0,1";
  $this->logs_array[]=array("log","PDO queryUniqueObject: ".$sql);
  // check for cache
  if($cache){
   $return=$this->getQueryFromCache($sql);
   if($return!==false){return $return;}
  }
  // execute query
  try{
   $results=$this->connection->query($sql);
   $return=$results->fetch(PDO::FETCH_OBJ);
   if(DEBUG){$this->logs_array[]=array("log","PDO queryUniqueObject result:\n".var_export($return,true));}
  }catch(PDOException $e){
   $this->logs_array[]=array("warn","PDO queryUniqueObject: ".$e->getMessage());
   $return=false;
  }
  //
  $this->query_counter++;
  if($cache){$this->addQueryToCache($sql,$return);}
  return $return;
 }


 public function queryUniqueValue($sql,$cache=true){
  $sql.=" LIMIT 0,1";
  $this->logs_array[]=array("log","PDO queryUniqueValue: ".$sql);
  // check for cache
  if($cache){
   $return=$this->getQueryFromCache($sql);
   if($return!==false){return $return;}
  }
  // execute query
  try{
   $results=$this->connection->query($sql);
   $return=$results->fetch(PDO::FETCH_NUM)[0];
   if(DEBUG){$this->logs_array[]=array("log","PDO queryUniqueValue result: ".$return);}
   }catch(PDOException $e){
   $this->logs_array[]=array("warn","PDO queryUniqueValue: ".$e->getMessage());
   $return=false;
  }
  //
  $this->query_counter++;
  if($cache){$this->addQueryToCache($sql,$return);}
  return $return;
 }


 public function queryInsert($table,$object){
  $fields_array=array();
  $results=$this->connection->query("SHOW COLUMNS FROM `".$table."`");
  foreach($results->fetchAll(PDO::FETCH_OBJ) as $field){$fields_array[$field->Field]=$field;}
  $sql="INSERT INTO `".$table."` (";
  foreach(array_keys(get_object_vars($object)) as $key){
   if(!array_key_exists($key,$fields_array) || $object->$key==""){unset($object->$key);continue;}
   $sql.="`".$key."`,";
  }
  $sql=substr($sql,0,-1).") VALUES (";
  foreach(array_keys(get_object_vars($object)) as $key){$sql.=":".$key.",";}
  $sql=substr($sql,0,-1).")";
  $this->logs_array[]=array("log","PDO queryInsert: ".$sql."\n".var_export($object,true));
  try{
   $query=$this->connection->prepare($sql);
   $query->execute(get_object_vars($object));
   $return=$this->connection->lastInsertId();
  }catch(PDOException $e){
   $this->logs_array[]=array("error","PDO queryInsert: ".$e->getMessage());
   $return=false;
  }
  $this->query_counter++;
  return $return;
 }


 public function queryUpdate($table,$object,$idKey="id"){
  $fields_array=array();
  $results=$this->connection->query("SHOW COLUMNS FROM `".$table."`");
  foreach($results->fetchAll(PDO::FETCH_OBJ) as $field){$fields_array[$field->Field]=$field;}
  $sql="UPDATE `".$table."` SET ";
  foreach(array_keys(get_object_vars($object)) as $key){
   if(!array_key_exists($key,$fields_array)){unset($object->$key);continue;}
   if($object->$key==""){$object->$key=null;}
   if($key<>$idKey){$sql.="`".$key."`=:".$key.",";}
  }
  $sql=substr($sql,0,-1)." WHERE `".$idKey."`=:".$idKey."";
  $this->logs_array[]=array("log","PDO queryUpdate: ".$sql."\n".var_export($object,true));
  try{
   $query=$this->connection->prepare($sql);
   $query->execute(get_object_vars($object));
   $return=$object->$idKey;
  }catch(PDOException $e){
   $this->logs_array[]=array("error","PDO queryUpdate: ".$e->getMessage());
   $return=false;
  }
  $this->query_counter++;
  return $return;
 }


 public function queryDelete($table,$id,$idKey="id"){
  $sql="DELETE FROM `".$table."` WHERE `".$idKey."`='".$id."'";
  $this->logs_array[]=array("log","PDO queryDelete: ".$sql);
  try{
   $query=$this->connection->query($sql);
   $this->logs_array[]=array("warn","PDO queryDelete: ".$query->rowCount()." rows deleted");
   $return=true;
  }catch(PDOException $e){
   $this->logs_array[]=array("error","PDO queryDelete: ".$e->getMessage());
   $return=false;
  }
  $this->query_counter++;
  return $return;
 }


 public function queryCount($table,$where="1"){
  $sql="SELECT COUNT(*) FROM `".$table."` WHERE ".$where;
  $this->logs_array[]=array("log","PDO queryCount: ".$sql);
  try{
   $results=$this->connection->query($sql);
   $return=$results->fetchColumn();
  }catch(PDOException $e){
   $this->logs_array[]=array("error","PDO queryCount: ".$e->getMessage());
   $return=false;
  }
  $this->query_counter++;
  return $return;
 }

}

?>
