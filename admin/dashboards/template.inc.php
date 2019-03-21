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
$nav->addItem("Dashboards","admin.php?mod=".MODULE."&scr=dashboard_list");
// operations
if($dashboard_obj->id && in_array(SCRIPT,array("dashboard_view","dashboard_edit"))){
 $nav->addItem("Operations",null,null,"active");
 $nav->addSubItem("Edit dashboard","admin.php?mod=".MODULE."&scr=dashboard_edit&idDashboard=".$dashboard_obj->id);
 $nav->addSubItem("Reorder tiles","admin.php?mod=".MODULE."&scr=submit&act=dashboard_tile_reorder&idDashboard=".$dashboard_obj->id);
 $nav->addSubItem("Add new tile","admin.php?mod=".MODULE."&scr=dashboard_view&act=tile_add&idDashboard=".$dashboard_obj->id);
 $nav->addSubItem("Preview","index.php?dashboard=".$dashboard_obj->id,true,null,null,null,null,"_blank");
}else{$nav->addItem("Add new dashboard","admin.php?mod=".MODULE."&scr=dashboard_edit");}
// renderize nav into bootstrap sections
$bootstrap->addSection($nav->render(false,3));
