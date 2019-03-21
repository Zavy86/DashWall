<?php
/**
 * Dashboard
 *
 * @package DashWall\Admin\Authentication
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 // include template
 require_once("template.inc.php");
 // set title
 $bootstrap->setTitle("Authentication");
 // build form
 $form=new strForm("admin.php?mod=".MODULE."&scr=submit&act=login","POST",null,"authentication");
 $form->addField("password","password","Authentication",null,"Insert the authentication code..",null,null,null,"required autofocus");
 $form->addFieldAddonButton("#","Submit","btn-primary",false,"onClick=\"document.getElementById('form_authentication').submit();\"");
 // build grid
 $grid=new strGrid();
 // add grid row
 $grid->addRow();
 // renderize form list into grid
 $grid->addCol($form->render(null,6),"col-xs-12");
 // renderize grid into bootstrap sections
 $bootstrap->addSection($grid->render(true,3));
