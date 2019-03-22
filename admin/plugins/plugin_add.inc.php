<?php
/**
 * Plugin Add
 *
 * @package DashWall\Admin\Plugins
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 checkAuthorizations();
 // include template
 require_once("template.inc.php");
 // set title
 $bootstrap->setTitle("Add new plugin");
 // build form
 $form=new strForm("admin.php?mod=".MODULE."&scr=submit&act=plugin_add","POST",null,"plugin_add");
 $form->addField("text","plugin","Plugin",null,"Enter the plugin name",null,null,null,"required");
 $form->addControl("submit","Submit");
 $form->addControl("button","Cancel","admin.php?mod=".MODULE."&scr=plugin_list");
 // build grid
 $grid=new strGrid();
 // add grid row
 $grid->addRow();
 // renderize description list into grid
 $grid->addCol($form->render(0,6),"col-xs-12");
 // renderize grid into bootstrap sections
 $bootstrap->addSection($grid->render(true,3));
