<?php
/**
 * Schedule List - Log
 *
 * @package DashWall\Admin\Schedules
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 checkAuthorizations();
 // check for actions
 if(ACTION=="log_view"){
  // get selected tile
  $selected_schedule_obj=new Schedule($_REQUEST['idSchedule']);
  // definitions
  $execution_log_file=$APP->dir."logs/".$selected_schedule_obj->id.".execution.log";
  $verbose_log_file=$APP->dir."logs/".$selected_schedule_obj->id.".verbose.log";
  // check for log files
  if(file_exists($execution_log_file)){
   $execution_log=file_get_contents($execution_log_file);
   $execution_log_timestamp=filemtime($execution_log_file);
   if(file_exists($verbose_log_file)){$execution_log_verbose=file_get_contents($verbose_log_file);}
  }
  // build description list
  $dl_log=new strDescriptionList("br","dl-horizontal");
  if(file_exists($execution_log_file)){
   $dl_log->addElement("Execution",api_timestamp_format($execution_log_timestamp));
   $dl_log->addElement("Result",api_tag("pre",$execution_log));
   if(strlen($execution_log_verbose)){$dl_log->addElement("Log",api_tag("pre",$execution_log_verbose));}
  }else{
   $dl_log->addElement("Execution",api_tag("em","Log not found.."));
  }
  // build modal window
  $tile_modal=new strModal($selected_schedule_obj->title." Log",null,"schedule_view-tile_modal");
  $tile_modal->setBody($dl_log->render());
  // add modal to boostrap
  $GLOBALS['bootstrap']->addModal($tile_modal);
  // jQuery scripts
  $GLOBALS['bootstrap']->addScript("/* Modal window opener */\n$(function(){\$('#modal_schedule_view-tile_modal').modal('show');});");
 }
