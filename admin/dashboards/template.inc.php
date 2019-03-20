<?php
/**
 * Template
 *
 * @package DashWall\Admin\Dashboards
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
checkAuthorizations();
// build navigation
$nav=new strNav("nav-tabs");
$nav->setTitle("Dashboards");
// customers
if(substr($_REQUEST['scr'],0,9)=="dashboard"){
 $nav->addItem("Dashboards","admin.php?mod=dashboards&scr=dashboard_list");
 // operations
 if($dashboard_obj->id && in_array($_REQUEST['scr'],array("dashboard_view","dashboard_edit"))){
  $nav->addItem("Operations",null,null,"active");
  $nav->addSubItem("Edit dashboard","admin.php?mod=dashboards&scr=dashboard_edit&idDashboard=".$dashboard_obj->id);
  $nav->addSubItem("Reorder tiles","admin.php?mod=dashboards&scr=submit&act=dashboard_tile_reorder&idDashboard=".$dashboard_obj->id);
  $nav->addSubItem("Add new tile","admin.php?mod=dashboards&scr=dashboard_view&act=tile_add&idDashboard=".$dashboard_obj->id);
 }else{$nav->addItem("Add","admin.php?mod=dashboards&scr=dashboard_edit");}
}
// renderize nav into bootstrap sections
$bootstrap->addSection($nav->render(false,3));
