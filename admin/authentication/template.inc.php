<?php
/**
 * Template
 *
 * @package DashWall\Admin\Authentication
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
// build navigation
$nav=new strNav("nav-tabs");
$nav->setTitle("Authentication");
$nav->addItem(api_icon("th-large"),"admin.php?mod=".MODULE);
// renderize nav into bootstrap sections
$bootstrap->addSection($nav->render(false,3));