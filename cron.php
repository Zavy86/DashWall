<?php
/**
 * Cron
 *
 * @package DashWall
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 echo "Starting..";
 // enable implicit flushing
 ob_end_flush();
 ob_implicit_flush();
 // load application
 require_once("loader.inc.php");
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
  echo "<br>Updating ".$schedule_fobj->title."..";
  // build post data
  $post_data=$schedule_fobj->parameters_array;
  $post_data['plugin']=$schedule_fobj->plugin;
  // build http options
  $options=array(
   "http"=>array(
    "header"=>"Content-type: application/x-www-form-urlencoded\r\n",
    "method"=>"POST",
    "content"=>http_build_query($post_data),
    "timeout"=>3600
   ),
  );
  // make stram context
  $context=stream_context_create($options);
  // get from http
  $response=file_get_contents($APP->url."pud.php",false,$context);
  // decode result
  $return=json_decode($response,true);
  // check response
  if($return['error']){echo " [Failed]";}else{echo " [Ok]";}
  // debug
  api_dump($return,$schedule_fobj->title);
 }
 // end
 echo "<br>Completed!";
 // debug
 api_dump($APP,"Dash|Wall");
 api_dump($DB,"Database");
