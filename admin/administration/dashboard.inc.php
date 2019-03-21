<?php
/**
 * Dashboard
 *
 * @package DashWall\Admin\Administration
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 checkAuthorizations();
 // include template
 require_once("template.inc.php");
 // set title
 $bootstrap->setTitle("Administration");
 // definitions
 $dashboard=null;
 // build dashboard buttons
 $dashboard.=str_repeat(" ",6).api_link("admin.php?mod=dashboards",api_icon("th",null,"fa-4x")."<br><br>Dashboards","Manage dashboards","btn btn-default btn-lg btn-dashboard")."\n";
 $dashboard.=str_repeat(" ",6).api_link("admin.php?mod=datasets",api_icon("file-text",null,"fa-4x")."<br><br>Datasets","Manage datasets","btn btn-default btn-lg btn-dashboard")."\n";
 $dashboard.=str_repeat(" ",6).api_link("admin.php?mod=datasources",api_icon("database",null,"fa-4x")."<br><br>Datasources","Manage datasources","btn btn-default btn-lg btn-dashboard")."\n";
 $dashboard.=str_repeat(" ",6).api_link("admin.php?mod=schedules",api_icon("clock-o",null,"fa-4x")."<br><br>Schedules","Manage schedules","btn btn-default btn-lg btn-dashboard")."\n";
 $dashboard.=str_repeat(" ",6).api_link("admin.php?mod=authentication&scr=submit&act=logout",api_icon("lock",null,"fa-4x")."<br><br>Lock","Administration logout","btn btn-default btn-lg btn-dashboard")."\n";
 // build grid
 $grid=new strGrid();
 // add grid row
 $grid->addRow();
 // renderize dashboard list into grid
 $grid->addCol($dashboard,"col-xs-12");
 // renderize grid into bootstrap sections
 $bootstrap->addSection($grid->render(true,3));
