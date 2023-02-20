<?php
/**
 * Template
 *
 * @package DashWall\Admin\Datasets
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
checkAuthorizations();
// build navigation
$nav=new strNav("nav-tabs");
$nav->setTitle("Datasets");
$nav->addItem("Datasets","admin.php?mod=".MODULE."&scr=dataset_list");
// operations
if(isset($r_dataset) && in_array(SCRIPT,array("dataset_view","dataset_edit"))){ /** @todo edit? */
 $nav->addItem("Operations",null,null,"active");
 $nav->addSubItem("Add new data to dataset","admin.php?mod=".MODULE."&scr=dataset_edit&dataset=".$r_dataset);
}else{/*$nav->addItem("Add new dataset","admin.php?mod=".MODULE."&scr=dataset_edit");*/}
// renderize nav into bootstrap sections
$bootstrap->addSection($nav->render(false,3));
