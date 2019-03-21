<?php
/**
 * Schedule List
 *
 * @package DashWall\Admin\Schedules
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 checkAuthorizations();
 // include template
 require_once("template.inc.php");
 // set title
 $bootstrap->setTitle("Schedules");
 // build table
 $table=new strTable("There is no schedule to show..");
 // add table headers
 $table->addHeader("&nbsp;");
 $table->addHeader("Schedule",null,"100%");
 $table->addHeader("Plugin","nowrap text-right");
 $table->addHeader("Hours","nowrap text-right");
 $table->addHeader("Minutes","nowrap text-right");
 // get schedules
 $schedules_array=array();
 $results=$GLOBALS['DB']->queryObjects("SELECT * FROM `dashwall__schedules` ORDER BY `title` ASC");
 foreach($results as $result){$schedules_array[$result->id]=new Schedule($result);}
 // cycle all schedules
 foreach($schedules_array as $schedule_fobj){
  // check selected
  if($schedule_fobj->id==$_REQUEST['idSchedule']){$tr_class="info";}else{$tr_class=null;}
  // add table datas
  $table->addRow($tr_class);
  $table->addRowFieldAction("admin.php?mod=schedules&scr=schedule_edit&idSchedule=".$schedule_fobj->id,api_icon("pencil","Edit this schedule"));
  $table->addRowField($schedule_fobj->title);
  $table->addRowField(api_tag("samp",$schedule_fobj->plugin),"nowrap text-right");
  $table->addRowField($schedule_fobj->hours,"nowrap text-right");
  $table->addRowField($schedule_fobj->minutes,"nowrap text-right");
 }
 // make alert
 $alert=api_tag("div","Sample crontab script: <samp>*  *  *  *  *  wget \"".$APP->url."cron.php\" -qO- | cat /dev/null</samp>","alert alert-info");
 // build grid
 $grid=new strGrid();
 // add grid row
 $grid->addRow();
 // renderize alert into grid
 $grid->addCol($alert,"col-xs-12");
 // add grid row
 $grid->addRow();
 // renderize table into grid
 $grid->addCol($table->render(6),"col-xs-12");
 // renderize grid into bootstrap sections
 $bootstrap->addSection($grid->render(true,3));
