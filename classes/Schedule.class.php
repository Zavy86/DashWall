<?php
/**
 * Schedule
 *
 * @package DashWall\Classes
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */

/**
 * Schedule class
 */
class Schedule{

 /** Properties */
 protected $id;
 protected $title;
 protected $minutes;
 protected $hours;
 protected $plugin;
 protected $parameters;

 /**
  * Constructor
  */
 public function __construct($schedule){
  // load object
  if(is_numeric($schedule)){$schedule=$GLOBALS['DB']->queryUniqueObject("SELECT * FROM `dashwall__schedules` WHERE `id`='".$schedule."'");}
  if(!$schedule->id){return false;}
  // initialize properties
  $this->id=(int)$schedule->id;
  $this->title=stripslashes($schedule->title);
  $this->minutes=stripslashes($schedule->minutes);
  $this->hours=stripslashes($schedule->hours);
  $this->plugin=stripslashes($schedule->plugin);
  $this->parameters_array=json_decode(stripslashes($schedule->parameters),true);
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

}
