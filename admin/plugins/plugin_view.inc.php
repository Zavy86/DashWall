<?php
/**
 * Plugin View
 *
 * @package DashWall\Admin\Plugins
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 checkAuthorizations();
 // acquire variables
 $r_plugin=$_REQUEST['plugin'];
 // checks
 if(!is_dir($APP->dir."plugins/".$r_plugin)){api_alert("Plugin not found","danger");api_redirect("admin.php?mod=".MODULE."&scr=plugin_list");}
 // include template
 require_once("template.inc.php");
 // set title
 $bootstrap->setTitle("Plugin ".strtoupper($r_plugin));
 // definitions
 $files_array=array();
 // scan plugin directory
 $files=scandir($APP->dir."plugins/".$r_plugin);
 // cycle all elements
 foreach($files as $file_f){
  // skip versioning and files
  if(in_array($file_f,array(".","..","index.php"))){continue;}
  $files_array[]=$file_f;
 }
 // build table
 $table_file=new strTable("There is no file to show..");
 // cycle all plugins
 foreach($files_array as $file_f){
  // check selected
  if($file_f==$_REQUEST['file']){$tr_class="info";}else{$tr_class=null;}
  // add table datas
  $table_file->addRow($tr_class);
  $table_file->addRowFieldAction("admin.php?mod=plugins&scr=plugin_file_edit&plugin=".$r_plugin."&file=".$file_f,api_icon("pencil","Edit plugin file"));
  $table_file->addRowField(api_tag("samp",$file_f),"nowrap","width:100%");
 }
 // build description list
 $dl_left=new strDescriptionList("br","dl-horizontal");
 $dl_left->addElement("Plugin",api_tag("samp",$r_plugin));
 $dl_right=new strDescriptionList("br","dl-horizontal");
 $dl_right->addElement("Files","\n".$table_file->render(8).str_repeat(" ",7));
 // build grid
 $grid=new strGrid();
 // add grid row
 $grid->addRow();
 // renderize description lists into grid
 $grid->addCol($dl_left->render(6),"col-xs-12 col-md-4");
 $grid->addCol($dl_right->render(6),"col-xs-12 col-md-8");
 // renderize grid into bootstrap sections
 $bootstrap->addSection($grid->render(true,3));
