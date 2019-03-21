<?php
/**
 * Setup
 *
 * @package DashWall
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 // include functions
 require_once("functions.inc.php");
 // errors configuration
 ini_set("display_errors",true);
 error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
 // defines constants
 define('VERSION',file_get_contents("VERSION.txt"));
 define("PATH",explode("setup.php",$_SERVER['REQUEST_URI'])[0]);
 define('HOST',(isset($_SERVER['HTTPS'])?"https":"http")."://".$_SERVER['HTTP_HOST']);
 define('ROOT',rtrim(str_replace("\\","/",realpath(dirname(__FILE__))."/"),PATH));
 define('URL',HOST.PATH);
 define('DIR',ROOT.PATH);
 // die if configuration already exist
 if(file_exists(DIR."config.inc.php")){die("Dash|Wall is already configured..");}
 // include bootstrap structures
 require_once(DIR."structures/strBootstrap.class.php");
 // globals variables
 global $bootstrap;
 // build bootstrap structure
 $bootstrap=new strBootstrap(PATH);
 // build navbar
 $navbar=new strNavbar("Dash|Wall");
 // add navbar to bootstrap
 $bootstrap->addSection($navbar->render(3));
 // switch setup actions
 switch($_REQUEST['setup_action']){
  case "check":setup_check();break;
  case "setup":setup_setup();break;
  default:setup_form();
 }
 // build footer grid
 $footer_grid=new strGrid();
 $footer_grid->addRow("footer");
 $footer_grid->addCol(str_repeat(" ",6).api_tag("div","Copyright 2018-".date("Y")." &copy; <a href=\"https://github.com/Zavy86/dashwall\" target=\"_blank\">Dash|Wall</a> - All Rights Reserved","text-right")."\n","col-xs-12");
 // add footer grid to bootstrap
 $bootstrap->addSection(str_repeat(" ",3)."<hr>\n".$footer_grid->render(true,3));
 // renderize bootstrap
 $bootstrap->render();

 /**
  * Form
  */
 function setup_form(){
  // build setup form
  $form=new strForm("setup.php","POST",null,"setup");
  // setup form
  $form->addField("hidden","setup_action",null,"check");
  $form->addField("text","path","Path",PATH,"Directory with trailing slash",null,null,null,"required");
  $form->addField("text","authentication","Authentication",null,"Administration password",null,null,null,"required");
  $form->addField("select","db_type","Database typology","mysql","Select one database..",null,null,null,"required");
  $form->addFieldOption("mysql","MySQL");
  $form->addField("text","db_host","Host",null,"Hostname or IP Address",null,null,null,"required");
  $form->addField("text","db_port","Port",3306,"Port number",null,null,null,"required");
  $form->addField("text","db_name","Database",null,"Database name",null,null,null,"required");
  $form->addField("text","db_user","Username",null,"Database username",null,null,null,"required");
  $form->addField("text","db_pass","Password",null,"Database password",null,null,null,"required");
  $form->addControl("submit","Check parameters");
  // build grid
  $grid=new strGrid();
  // add grid row
  $grid->addRow();
  // renderize form list into grid
  $grid->addCol($form->render(null,6),"col-xs-12");
  // renderize grid into bootstrap sections
  $GLOBALS['bootstrap']->addSection($grid->render(true,3));
 }

 /**
  * Check
  */
 function setup_check(){
  // set configuration object
  $configuration=new stdClass();
  $configuration->path=$_REQUEST['path'];
  $configuration->db_type=$_REQUEST['db_type'];
  $configuration->db_host=$_REQUEST['db_host'];
  $configuration->db_port=$_REQUEST['db_port'];
  $configuration->db_name=$_REQUEST['db_name'];
  $configuration->db_user=$_REQUEST['db_user'];
  $configuration->db_pass=$_REQUEST['db_pass'];
  $configuration->authentication=$_REQUEST['authentication'];
  // check parameters
  if(!substr($configuration->path,-1)=="/"){$configuration->path.="/";}
  // try database connection
  try{
   $connection=new PDO($configuration->db_type.":host=".$configuration->db_host.";port=".$configuration->db_port.";dbname=".$configuration->db_name.";charset=utf8",$configuration->db_user,$configuration->db_pass);
   $connection->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND,"SET NAMES utf8");
   $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
   $connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
  }catch(PDOException $e){
   die("PDO connection: ".$e->getMessage());
  }
  // check writable permission
  $fh=fopen(DIR."config.inc.php","w");
  if(!$fh){die("Error trying to write configuration file: ".DIR."config.inc.php");}
  fclose($fh);
  unlink(DIR."config.inc.php");
  // build check form
  $form=new strForm("setup.php","POST",null,"setup");
  $form->addField("hidden","setup_action",null,"setup");
  $form->addField("hidden","path",null,$configuration->path);
  $form->addField("hidden","db_type",null,$configuration->db_type);
  $form->addField("hidden","db_host",null,$configuration->db_host);
  $form->addField("hidden","db_port",null,$configuration->db_port);
  $form->addField("hidden","db_name",null,$configuration->db_name);
  $form->addField("hidden","db_user",null,$configuration->db_user);
  $form->addField("hidden","db_pass",null,$configuration->db_pass);
  $form->addField("hidden","authentication",null,$configuration->authentication);
  $form->addField("static",null,"Check permissions","<i class='fa fa-check'></i> Ok");
  $form->addField("static",null,"Check parameters","<i class='fa fa-check'></i> Ok");
  $form->addControl("submit","Setup");
  // build grid
  $grid=new strGrid();
  // add grid row
  $grid->addRow();
  // renderize form list into grid
  $grid->addCol($form->render(null,6),"col-xs-12");
  // renderize grid into bootstrap sections
  $GLOBALS['bootstrap']->addSection($grid->render(true,3));
 }

 /**
  * Setup
  */
 function setup_setup(){
  // build configuration file
  $file_content="<?php\n";
  $file_content.=" // directory\n";
  $file_content.=" \$configuration->path=\"".$_REQUEST['path']."\";\n";
  $file_content.=" // database parameters\n";
  $file_content.=" \$configuration->db_type=\"".$_REQUEST['db_type']."\";\n";
  $file_content.=" \$configuration->db_host=\"".$_REQUEST['db_host']."\";\n";
  $file_content.=" \$configuration->db_port=\"".$_REQUEST['db_port']."\";\n";
  $file_content.=" \$configuration->db_name=\"".$_REQUEST['db_name']."\";\n";
  $file_content.=" \$configuration->db_user=\"".$_REQUEST['db_user']."\";\n";
  $file_content.=" \$configuration->db_pass=\"".$_REQUEST['db_pass']."\";\n";
  $file_content.=" // authentication\n";
  $file_content.=" \$configuration->authentication=\"".$_REQUEST['authentication']."\";\n";
  $file_content.="?>";
  // write configuration file
  file_put_contents(DIR."config.inc.php",$file_content);
  // change configuration file permissions
  chmod(DIR."config.inc.php",0755);
  // load setup dump
  $queries=file(DIR."queries/setup.sql");
  // try database connection
  try{
   $connection=new PDO($_REQUEST['db_type'].":host=".$_REQUEST['db_host'].";port=".$_REQUEST['db_port'].";dbname=".$_REQUEST['db_name'].";charset=utf8",$_REQUEST['db_user'],$_REQUEST['db_pass']);
   $connection->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND,"SET NAMES utf8");
   $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
   $connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
  }catch(PDOException $e){
   die("PDO connection: ".$e->getMessage());
  }
  // cycle all queries
  foreach($queries as $line){
   // skip comments
   if(substr($line,0,2)=="--" || $line==""){continue;}
   $sql_query=$sql_query.$line;
   // search for query end signal
   if(substr(trim($line),-1,1)==';'){
    // execute query
    try{
     $query=$connection->prepare($sql_query);
     $query->execute();
    }catch(PDOException $e){die("PDO queryError: ".$e->getMessage());}
    // reset query
    $sql_query="";
   }
  }
  // build setup form
  $form=new strForm("setup.php","POST",null,"setup");
  $form->addField("hidden","setup_action",null,"completed");
  $form->addField("static",null,"Setup","<i class='fa fa-check'></i> Completed");
  $form->addControl("button","Administration","admin.php","btn-primary");
  // build grid
  $grid=new strGrid();
  // add grid row
  $grid->addRow();
  // renderize form list into grid
  $grid->addCol($form->render(null,6),"col-xs-12");
  // renderize grid into bootstrap sections
  $GLOBALS['bootstrap']->addSection($grid->render(true,3));
 }
