<?php
/**
 * Template
 *
 * @package DashWall\Admin\Plugins
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
checkAuthorizations();
// build navigation
$nav=new strNav("nav-tabs");
$nav->setTitle("Plugins");
$nav->addItem("Plugins","admin.php?mod=".MODULE."&scr=plugin_list");
// operations
if($r_plugin && in_array(SCRIPT,array("plugin_view","plugin_edit","plugin_file_edit"))){
 $nav->addItem("Operations",null,null,"active");
 //$nav->addSubItem("Edit plugin","admin.php?mod=".MODULE."&scr=plugin_edit&plugin=".$r_plugin);
 $nav->addSubItem("Add new file","admin.php?mod=".MODULE."&scr=plugin_file_edit&plugin=".$r_plugin);
}else{$nav->addItem("Add new plugin","admin.php?mod=".MODULE."&scr=plugin_add");}
// renderize nav into bootstrap sections
$bootstrap->addSection($nav->render(false,3));
