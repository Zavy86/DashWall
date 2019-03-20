<?php
/**
 * Administration
 *
 * @package DashWall
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */

 // include functions
 require_once("functions.inc.php");
 // include bootstrap structures
 require_once(DIR."structures/strBootstrap.class.php");
 // acquire variables
 $r_script=$_REQUEST['scr'];
 $r_module=$_REQUEST['mod'];
 $r_action=$_REQUEST['act'];
 // module, script ad action definitions
 if($r_module){define(MODULE,$r_module);}else{define(MODULE,"administration");}
 if($r_script){define(SCRIPT,$r_script);}else{define(SCRIPT,"dashboard");}
 if($r_action){define(ACTION,$r_action);}else{define(ACTION,null);}
 // globals variables
 global $bootstrap;


 // template ------------

 // build bootstrap structure
 $bootstrap=new strBootstrap($APP->path);


 //
 $navbar=new strNavbar($APP->title);
 $navbar->addNav();

 $navbar->addElement("Administration","admin.php?mod=administration");
 $navbar->addElement("Dashboards","admin.php?mod=dashboards");

 //
 $bootstrap->addSection($navbar->render(3));

 // /template ----------


 // check and import script
 if(!is_dir($APP->dir."admin/".MODULE)){api_alert("Module \"".MODULE."\" was not found..","danger");api_redirect($APP->path."admin.php");}
 if(!file_exists($APP->dir."admin/".MODULE."/".SCRIPT.".inc.php")){api_alert("Script \"".SCRIPT."\" was not found in module \"".MODULE."\"..","danger");}
 else{require_once($APP->dir."admin/".MODULE."/".SCRIPT.".inc.php");}


 // renderize
 $bootstrap->render();
 // debug
 api_dump($APP,"Dash|Wall");
 api_dump($DB,"Database");