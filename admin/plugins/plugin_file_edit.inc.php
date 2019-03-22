<?php
/**
 * Plugin File Edit
 *
 * @package DashWall\Admin\Plugins
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 checkAuthorizations();
 // acquire variables
 $r_plugin=$_REQUEST['plugin'];
 $r_file=$_REQUEST['file'];
 // include template
 require_once("template.inc.php");
 // checks
 if(!is_dir($APP->dir."plugins/".$r_plugin)){api_alert("Plugin not found","danger");api_redirect("admin.php?mod=".MODULE."&scr=plugin_list");}
 if($r_file && !file_exists($APP->dir."plugins/".$r_plugin."/".$r_file)){api_alert("File not found","danger");api_redirect("admin.php?mod=".MODULE."&scr=plugin_view&plugin=".$r_plugin);}
 // set title
 if($r_file){$bootstrap->setTitle("Plugin ".$r_plugin." Edit file ".$r_file);}else{$bootstrap->setTitle("Plugin ".$r_plugin." Add new file");}
 // load source
 if($r_file){$source=file_get_contents($APP->dir."plugins/".$r_plugin."/".$r_file);}
 // build description list
 $dl=new strDescriptionList("br","dl-horizontal");
 $dl->addElement("Plugin",api_tag("samp",$r_plugin));
 // build form
 $form=new strForm("admin.php?mod=".MODULE."&scr=submit&act=plugin_file_save&plugin=".$r_plugin."&file=".$r_file,"POST",null,"plugin_file_edit");
 $form->addField("text","filename","Filename",$r_file,"Enter the file name",null,null,"font-family:monospace");
 $form->addField("textarea","source","Source",htmlspecialchars($source),"Script source code..",null,null,"font-family:monospace","rows=18");
 $form->addControl("submit","Submit");
 $form->addControl("button","Cancel","admin.php?mod=".MODULE."&scr=plugin_view&plugin=".$r_plugin."&file=".$r_file);
 if($r_file){$form->addControl("button","Remove","admin.php?mod=".MODULE."&scr=submit&act=plugin_file_remove&plugin=".$r_plugin."&file=".$r_file,"btn-danger","Are you sure you want to remove definitively this file from plugin?");}
 // build grid
 $grid=new strGrid();
 // add grid row
 $grid->addRow();
 // renderize description list into grid
 $grid->addCol($dl->render(6),"col-xs-12");
 $grid->addCol($form->render(0,6),"col-xs-12");
 // renderize grid into bootstrap sections
 $bootstrap->addSection($grid->render(true,3));
