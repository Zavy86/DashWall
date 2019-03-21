<?php
/**
 * Dashboard View - Tile
 *
 * @package DashWall\Admin\Dashboards
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 checkAuthorizations();
 // check for actions
 if(in_array(ACTION,array("tile_add","tile_edit"))){
  // get selected tile
  $selected_tile_obj=$dashboard_obj->getTile($_REQUEST['idTile']);
  // build form
  $tile_form=new strForm("admin.php?mod=".MODULE."&scr=submit&act=dashboard_tile_save&idDashboard=".$dashboard_obj->id."&idTile=".$selected_tile_obj->id,"POST",null,"dashboard_view-tile");
  // title
  $tile_form->addField("text","title","Title",$selected_tile_obj->title,"Tile title",null,null,null,"required");
  // size
  $tile_form->addField("select","width","Width",$selected_tile_obj->width,"Select a width..",null,null,null,"required");
  for($w=1;$w<=6;$w++){$tile_form->addFieldOption($w,$w." unit");}
  $tile_form->addField("select","height","Height",$selected_tile_obj->height,"Select an height..",null,null,null,"required");
  for($h=1;$h<=6;$h++){$tile_form->addFieldOption($h,$h." unit");}
  // refresh
  $tile_form->addField("select","refresh","Refresh",$selected_tile_obj->parameters_array['refresh'],"Select a refresh timeout..",null,null,null,"required");
  $tile_form->addFieldOption("0","Disabled");
  $tile_form->addFieldOption("1000","1 second");
  $tile_form->addFieldOption("5000","5 seconds");
  $tile_form->addFieldOption("10000","10 seconds");
  $tile_form->addFieldOption("30000","30 seconds");
  $tile_form->addFieldOption("60000","1 minute");
  $tile_form->addFieldOption("300000","5 minutes");
  $tile_form->addFieldOption("600000","10 minutes");
  $tile_form->addFieldOption("900000","15 minutes");
  $tile_form->addFieldOption("1800000","30 minutes");
  $tile_form->addFieldOption("3600000","1 hour");
  $tile_form->addFieldOption("14400000","4 hour");
  $tile_form->addFieldOption("21600000","6 hour");
  $tile_form->addFieldOption("43200000","12 hour");
  $tile_form->addFieldOption("86400000","1 day");
  // plugins
  $tile_form->addField("select","plugin","Plugin",$selected_tile_obj->plugin,"Select a plugin..",null,null,null,"required");
  // scan plugin directory
  $plugins=scandir($APP->dir."plugins");
  // cycle all elements
  foreach($plugins as $plugin_f){
   // skip versioning and files
   if(in_array($plugin_f,array(".","..","index"))){continue;}
   if(!is_dir($APP->dir."plugins/".$plugin_f)){continue;}
   $tile_form->addFieldOption($plugin_f,$plugin_f);
  }
  // parameters
  if($selected_tile_obj->id){
   $parameters=$selected_tile_obj->parameters_array;
   unset($parameters['refresh']);
   $parameters_text=json_encode($parameters,JSON_PRETTY_PRINT);
  }
  $tile_form->addField("textarea","parameters","Parameters",$parameters_text,"Parameters in JSON format\n{\n    ''parameter'':''value''\n}",null,null,"font-family:monospace","rows=4");
  // controls
  $tile_form->addControl("submit","Submit");
  $tile_form->addControl("button","Cancel","#",null,null,null,"data-dismiss='modal'");
  $tile_form->addControl("button","Remove","admin.php?mod=".MODULE."&scr=submit&act=dashboard_tile_remove&idDashboard=".$dashboard_obj->id."&idTile=".$selected_tile_obj->id,"btn-danger","Are you sure you want to remove definitively this tile?");
  // build modal window
  $tile_modal=new strModal(($selected_tile_obj->id?"Tile ".$dashboard_obj->name." edit":"Tile add"),null,"dashboard_view-tile_modal");
  $tile_modal->setBody($tile_form->render(2,7));
  // add modal to boostrap
  $GLOBALS['bootstrap']->addModal($tile_modal);
  // jQuery scripts
  $GLOBALS['bootstrap']->addScript("/* Modal window opener */\n$(function(){\$('#modal_dashboard_view-tile_modal').modal('show');});");
 }
