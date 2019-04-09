<?php
/**
 * Cron
 *
 * @package DashWall
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 set_time_limit(0);
 ignore_user_abort(true);
 // enable implicit flushing
 ob_end_flush();
 ob_implicit_flush();
 // definitions
 $cron_response=array();
 $cron_response[]=date("Y-m-d H:i:s")." Starting..";
 // load application
 require_once("loader.inc.php");
 // check for cron timestamp
 function check_cron($schedule_obj){
  // definitions
  $hour_check=false;
  $minute_check=false;
  // check hours
  $hours=explode(",",$schedule_obj->hours);
  foreach($hours as $hour){if($hour=="*" || $hour==intval(date('H'))){$hour_check=true;continue;}}
  // check monites
  if($hour_check){
   $minutes=explode(",",$schedule_obj->minutes);
   foreach($minutes as $minute){
    if($minute=="*"){return true;}
    if($minute==intval(date('i'))){return true;}
    if($minute=="*/5" && in_array(intval(date('i')),array(0,5,10,15,20,25,30,35,40,45,50,55))){return true;}
    if($minute=="*/15" && in_array(intval(date('i')),array(0,15,30,45))){return true;}
    if($minute=="*/30" && in_array(intval(date('i')),array(0,30))){return true;}
   }
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
  $updating_log="Updating ".$schedule_fobj->title."..";
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
  if($return['error']===false){$updating_log.=" [Ok]";}else{$updating_log.=" [Failed]";}
  $cron_response[]=$updating_log;
  // save log
  file_put_contents($APP->dir."logs/".$schedule_fobj->id.".execution.log",$updating_log);
  file_put_contents($APP->dir."logs/".$schedule_fobj->id.".verbose.log",json_encode($return,JSON_PRETTY_PRINT));
  // debug
  api_dump($return,$schedule_fobj->title);
 }
 // end
 $cron_response[]=date("Y-m-d H:i:s")." Completed!\n";
 // echo result
 echo implode("\n",$cron_response);
 // debug
 api_dump($APP,"Dash|Wall");
 api_dump($DB,"Database");
