<?php
/**
 * Submit
 *
 * @package DashWall\Admin\Schedules
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 checkAuthorizations();
 // switch action
 switch(ACTION){
  // schedules
  case "schedule_save":schedule_save();break;
  case "schedule_remove":schedule_remove();break;
  // default
  default:
   api_alert("Submit function for action <em>".ACTION."</em> was not found in module <em>".MODULE."</em>..","danger");
   api_redirect("admin.php?mod=".MODULE);
 }

 /**
  * Schedule Save
  */
 function schedule_save(){
  api_dump($_REQUEST,"_REQUEST");
  // check authorizations
  checkAuthorizations();
  // get objects
  $schedule_obj=new Schedule($_REQUEST['idSchedule']);
  api_dump($schedule_obj,"schedule object");
  // acquire and encode parameters
  $parameters=json_decode($_REQUEST['parameters'],true);

  // build query object
  $schedule_qobj=new stdClass();
  $schedule_qobj->id=$schedule_obj->id;
  $schedule_qobj->title=addslashes($_REQUEST['title']);
  $schedule_qobj->hours=addslashes($_REQUEST['hours']);
  $schedule_qobj->minutes=addslashes($_REQUEST['minutes']);
  $schedule_qobj->plugin=addslashes($_REQUEST['plugin']);
  $schedule_qobj->parameters=json_encode($parameters);
  // debug
  api_dump($schedule_qobj,"schedule query object");
  // check object
  if($schedule_obj->id){
   // update
   $GLOBALS['DB']->queryUpdate("dashwall__schedules",$schedule_qobj);
   // alert
   api_alert("Schedule updated","success");
  }else{
   // insert
   $schedule_qobj->id=$GLOBALS['DB']->queryInsert("dashwall__schedules",$schedule_qobj);
   // alert
   api_alert("Schedule created","success");
  }
  // redirect
  api_redirect("admin.php?mod=".MODULE."&scr=schedule_list&idSchedule=".$schedule_qobj->id);
 }

 /**
  * Schedule Remove
  */
 function schedule_remove(){
  api_dump($_REQUEST,"_REQUEST");
  // check authorizations
  checkAuthorizations();
  // get objects
  $schedule_obj=new Schedule($_REQUEST['idSchedule']);
  api_dump($schedule_obj,"schedule object");
  // check object
  if(!$schedule_obj->id){api_alert("Schedule not found","danger");api_redirect("admin.php?mod=".MODULE."&scr=schedule_list");}
  // remove division
  $deleted=$GLOBALS['DB']->queryDelete("dashwall__schedules",$schedule_obj->id);
  // check query result
  if(!$deleted){api_alert("An error has occurred","danger");api_redirect("admin.php?mod=".MODULE."&scr=schedule_list&idSchedule=".$schedule_obj->id);}
  // alert and redirect
  api_alert("Schedule removed","warning");
  api_redirect("admin.php?mod=".MODULE."&scr=schedule_list");
 }
