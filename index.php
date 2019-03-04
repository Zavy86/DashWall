<?php
/**
 * Index
 *
 * @package DashWall
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
// include functions
require_once("functions.inc.php");
// initialize dashboard
$dashboard=new Dashboard();
// renderize dashboard
$dashboard->render();
// debug
api_dump($dashboard,"Dashboard");
api_dump($APP,"Dash|Wall");
api_dump($DB,"Database");
