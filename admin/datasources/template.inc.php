<?php
/**
 * Template
 *
 * @package DashWall\Admin\Datasources
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
checkAuthorizations();
// build navigation
$nav=new strNav("nav-tabs");
$nav->setTitle("Datasources");
$nav->addItem("Datasources","admin.php?mod=".MODULE."&scr=datasource_list");
// operations
if($datasource_obj->id && in_array(SCRIPT,array("datasource_view","datasource_edit"))){
 $nav->addItem("Operations",null,null,"active");
 $nav->addSubItem("Edit datasource","admin.php?mod=".MODULE."&scr=datasource_edit&idDatasource=".$datasource_obj->id);
 $nav->addSubItem("Test connection","admin.php?mod=".MODULE."&scr=submit&act=datasource_test&idDatasource=".$datasource_obj->id);
}else{$nav->addItem("Add new datasource","admin.php?mod=".MODULE."&scr=datasource_edit");}
// renderize nav into bootstrap sections
$bootstrap->addSection($nav->render(false,3));
