<?php
/**
 * Plugin List
 *
 * @package DashWall\Admin\Plugins
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 checkAuthorizations();
 // include template
 require_once("template.inc.php");
 // set title
 $bootstrap->setTitle("Plugins");
 // build table
 $table=new strTable("There is no plugin to show..");
 // add table headers
 $table->addHeader("&nbsp;");
 $table->addHeader("Plugin",null,"100%");
 // get plugins
 $plugins_array=array();
 // scan plugin directory
 $plugins=scandir($APP->dir."plugins");
 // cycle all elements
 foreach($plugins as $plugin_f){
  // skip versioning and files
  if(in_array($plugin_f,array(".","..","index.php"))){continue;}
  if(!is_dir($APP->dir."plugins/".$plugin_f)){continue;}
  $plugins_array[]=$plugin_f;
 }
 // cycle all plugins
 foreach($plugins_array as $plugin_f){
  // check selected
  if($plugin_f==$_REQUEST['plugin']){$tr_class="info";}else{$tr_class=null;}
  // add table datas
  $table->addRow($tr_class);
  $table->addRowFieldAction("admin.php?mod=plugins&scr=plugin_view&plugin=".$plugin_f,api_icon("search","View plugin"));
  $table->addRowField(api_tag("samp",$plugin_f),"nowrap");
 }
 // build grid
 $grid=new strGrid();
 // add grid row
 $grid->addRow();
 // renderize table into grid
 $grid->addCol($table->render(6),"col-xs-12");
 // renderize grid into bootstrap sections
 $bootstrap->addSection($grid->render(true,3));
