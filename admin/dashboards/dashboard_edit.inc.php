<?php
/**
 * Dashboard Edit
 *
 * @package DashWall\Admin\Dashboards
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 checkAuthorizations();
 // get object
 $dashboard_obj=new Dashboard(($_REQUEST['idDashboard']??null));
 // include template
 require_once("template.inc.php");
 // set title
 if(!isset($dashboard_obj->id)){$bootstrap->setTitle("Add new dashboard");}
 else{$bootstrap->setTitle("Edit ".$dashboard_obj->title);}
 // build form
 $form=new strForm("admin.php?mod=".MODULE."&scr=submit&act=dashboard_save&idDashboard=".$dashboard_obj->id."&return_scr=".api_return_script("dashboard_view"),"POST",null,"dashboard_edit");
 $form->addField("text","code","Code",$dashboard_obj->code,"Dashboard code",null,null,null,"required");
 $form->addField("text","title","Dashboard",$dashboard_obj->title,"Dashboard title",null,null,null,"required");
 $form->addField("select","orientation","Orientation",$dashboard_obj->orientation,"Select an orientation..",null,null,null,"required");
 $form->addFieldOption("landscape","Landscape");
 $form->addFieldOption("portrait","Portrait");
 $form->addField("select","theme","Theme",$dashboard_obj->theme,"Select a theme..",null,null,null,"required");
 $form->addFieldOption("light","Light");
 $form->addFieldOption("dark","Dark");
 $form->addControl("submit","Submit");
 $form->addControl("button","Cancel","admin.php?mod=".MODULE."&scr=".api_return_script("dashboard_view")."&idDashboard=".$dashboard_obj->id);
 $form->addControl("button","Remove","admin.php?mod=".MODULE."&scr=submit&act=dashboard_remove&idDashboard=".$dashboard_obj->id,"btn-danger","Are you sure you want to remove definitively this dashboard?");
 // build grid
 $grid=new strGrid();
 // add grid row
 $grid->addRow();
 // renderize description list into grid
 $grid->addCol($form->render(0,6),"col-xs-12");
 // renderize grid into bootstrap sections
 $bootstrap->addSection($grid->render(true,3));
