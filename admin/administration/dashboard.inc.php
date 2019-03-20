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
 $dashboard.=str_repeat(" ",6).api_link("admin.php?mod=settings",api_icon("cog",null,"fa-4x")."<br><br>Settings","Dash|Wall Settings","btn btn-default btn-lg btn-dashboard")."\n";
 $dashboard.=str_repeat(" ",6).api_link("admin.php?mod=datasources",api_icon("database",null,"fa-4x")."<br><br>Datasources","Dash|Wall Datasources","btn btn-default btn-lg btn-dashboard")."\n";
 $dashboard.=str_repeat(" ",6).api_link("admin.php?mod=dashboards",api_icon("th",null,"fa-4x")."<br><br>Dashboards","Dash|Wall Dashboards","btn btn-default btn-lg btn-dashboard")."\n";
 // build grid
 $grid=new strGrid();
 // add grid row
 $grid->addRow();
 // renderize description list into grid
 $grid->addCol($dashboard,"col-xs-12");
 // renderize grid into bootstrap sections
 $bootstrap->addSection($grid->render(true,3));
