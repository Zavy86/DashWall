<?php
/**
 * Application
 *
 * @package DashWall\Classes
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */

/**
 * Application class
 */
class Application{

 /** Properties */
 protected $version;
 protected $path;
 protected $host;
 protected $root;
 protected $url;
 protected $dir;
 protected $db;
 protected $settings_array;

 /**
  * Constructor
  *
  * @param string $configuration
  * @param string $theme [dark|light]
  */
 public function __construct($configuration){
  if(!is_object($configuration)){die("Configuration not defined..");}
  // initialize properties
  $this->version=VERSION;
  $this->path=PATH;
  $this->host=HOST;
  $this->root=ROOT;
  $this->url=URL;
  $this->dir=DIR;
  $this->db=$configuration->db_name;
  $this->settings_array=array();
  // load settings
  $settings_results=$GLOBALS['DB']->queryObjects("SELECT * FROM `dashwall__settings` ORDER BY `setting` ASC");
  foreach($settings_results as $setting){$this->settings_array[$setting->setting]=$setting->value;}
 }

 /**
  * Get Property
  *
  * @param string $property Property name
  * @return type Property value
  */
 public function __get($property){
  if(property_exists("Application",$property)){return $this->$property;}
  elseif(array_key_exists($property,$this->settings_array)){return $this->settings_array[$property];}
  else{return false;}
 }

}
