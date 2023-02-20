<?php
/**
 * Template
 *
 * @package DashWall\Admin\Schedules
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
checkAuthorizations();
// build navigation
$nav=new strNav("nav-tabs");
$nav->setTitle("Schedules");
$nav->addItem("Schedules","admin.php?mod=".MODULE."&scr=schedule_list");
// operations
if(isset($schedule_obj) && $schedule_obj->id && SCRIPT=="schedule_edit"){$nav->addItem("Edit schedule","admin.php?mod=".MODULE."&scr=schedule_edit");}
else{$nav->addItem("Add new schedule","admin.php?mod=".MODULE."&scr=schedule_edit");}
// renderize nav into bootstrap sections
$bootstrap->addSection($nav->render(false,3));
