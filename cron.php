<?php
/**
 * Cron
 *
 * @package DashWall
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 // include functions
 require_once("functions.inc.php");
 // check for cron timestamp
 function check_cron($schedule_obj){
  if($schedule_obj->hours=="*" && $schedule_obj->minutes=="*"){return true;}
  if($schedule_obj->hours=="*" || $schedule_obj->hours==intval(date('H'))){
   if($schedule_obj->minutes=="*"){return true;}
   if($schedule_obj->minutes==intval(date('i'))){return true;}
   if($schedule_obj->minutes=="*/5" && in_array(intval(date('i')),array(0,5,10,15,20,25,30,35,40,45,50,55))){return true;}
   if($schedule_obj->minutes=="*/15" && in_array(intval(date('i')),array(0,15,30,45))){return true;}
   if($schedule_obj->minutes=="*/30" && in_array(intval(date('i')),array(0,30))){return true;}
  }
  return false;
 }
 // definitions
 $schedules_array=array();
 $executable_schedules_array=array();
 // get schedules
 $results=$GLOBALS['DB']->queryObjects("SELECT * FROM `dashwall__schedules` ORDER BY `title` ASC");
 foreach($results as $result){$schedules_array[$result->id]=new Schedule($result);}
 // debug
 api_dump($schedules_array,"schedules_array");
 // cycle all schedules
 foreach($schedules_array as $schedule_fobj){if(check_cron($schedule_fobj)){$executable_schedules_array[$schedule_fobj->id]=$schedule_fobj;}}
 // debug
 api_dump($executable_schedules_array,"executable_array");
 // cycle all executable schedules
 foreach($executable_schedules_array as $schedule_fobj){
  // build return object
  $return=new stdClass();
  $return->error=false;
  $return->errors=array();
  $return->input=$schedule_fobj->parameters_array;
  $return->output=null;
  // check for plugin functions
  if(!file_exists($APP->dir."plugins/".$schedule_fobj->plugin."/update.php")){
   $return->error=true;
   $return->errors[]="plugin_update_not_found";
  }else{
   // include plugin functions
   require_once($APP->dir."plugins/".$schedule_fobj->plugin."/update.php");
   // call plugin main function
   pud($return);
  }
  // debug
  api_dump($return,$schedule_fobj->title);
 }
 // debug
 api_dump($APP,"Dash|Wall");
 api_dump($DB,"Database");
