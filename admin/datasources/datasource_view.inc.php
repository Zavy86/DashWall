<?php
/**
 * Datasource View
 *
 * @package DashWall\Admin\Datasources
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 checkAuthorizations();
 // get object
 $datasource_obj=new Datasource($_REQUEST['idDatasource']);
 if(!$datasource_obj->id){api_alert("Datasource not found","danger");api_redirect("admin.php?mod=".MODULE."&scr=datasource_list");}
 // include template
 require_once("template.inc.php");
 // set title
 $bootstrap->setTitle("Datasource ".strtoupper($datasource_obj->code));
 // build description list
 $dl_left=new strDescriptionList("br","dl-horizontal");
 $dl_left->addElement("Code",api_tag("samp",$datasource_obj->code));
 $dl_left->addElement("Datasource",api_tag("strong",$datasource_obj->description));
 $dl_left->addElement("Connector",strtoupper($datasource_obj->connector));
 if($datasource_obj->database){$dl_left->addElement("Hostname",$datasource_obj->hostname);}
 if($datasource_obj->database){$dl_left->addElement("Database",$datasource_obj->database);}
 if($datasource_obj->username){$dl_left->addElement("Username",$datasource_obj->username);}
 if($datasource_obj->password){$dl_left->addElement("Password",str_repeat("*",strlen($datasource_obj->password)));}

 $dl_right=new strDescriptionList("br","dl-horizontal");
 if($datasource_obj->tns){$dl_right->addElement("TNS",api_tag("samp",nl2br($datasource_obj->tns)));}
 if($datasource_obj->queries){$dl_right->addElement("Queries",api_tag("samp",nl2br($datasource_obj->queries)));}
 // build grid
 $grid=new strGrid();
 // add grid row
 $grid->addRow();
 // renderize description lists into grid
 $grid->addCol($dl_left->render(6),"col-xs-12 col-md-4");
 $grid->addCol($dl_right->render(6),"col-xs-12 col-md-8");
 // renderize grid into bootstrap sections
 $bootstrap->addSection($grid->render(true,3));
