<?php
/**
 * Datasource Edit
 *
 * @package DashWall\Admin\Datasources
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 checkAuthorizations();
 // get object
 $datasource_obj=new Datasource($_REQUEST['idDatasource']);
 // include template
 require_once("template.inc.php");
 // set title
 if(!$datasource_obj->id){$bootstrap->setTitle("Add new datasource");}
 else{$bootstrap->setTitle("Edit ".$datasource_obj->title);}
 // build form
 $form=new strForm("admin.php?mod=".MODULE."&scr=submit&act=datasource_save&idDatasource=".$datasource_obj->id."&return_scr=".api_return_script("datasource_view"),"POST",null,"datasource_edit");
 $form->addField("text","code","Code",$datasource_obj->code,"Datasource code",null,null,null,"required");
 $form->addField("text","description","Datasource",$datasource_obj->description,"Datasource description",null,null,null,"required");
 $form->addField("text","hostname","Hostname",$datasource_obj->hostname,"Database hostname or IP address",null,null,null,"required");
 $form->addField("select","connector","Connector",$datasource_obj->connector,"Select a connector..",null,null,null,"required");
 $form->addFieldOption("mysql","MySQL");
 $form->addFieldOption("oci","Oracle Call Interface");
 $form->addField("text","database","Database",$datasource_obj->database,"Database name");
 $form->addField("text","username","Username",$datasource_obj->username,"Database username");
 $form->addField("text","password","Password",$datasource_obj->password,"Database password");
 $form->addField("textarea","tns","TNS",$datasource_obj->tns,"Datasource TNS for OCI connector..",null,null,"font-family:monospace","rows=4");
 $form->addField("textarea","queries","Queries",$datasource_obj->queries,"Additional comma separated queries to be executed after connection..",null,null,"font-family:monospace","rows=4");
 $form->addControl("submit","Submit");
 $form->addControl("button","Cancel","admin.php?mod=".MODULE."&scr=".api_return_script("datasource_view")."&idDatasource=".$datasource_obj->id);
 $form->addControl("button","Remove","admin.php?mod=".MODULE."&scr=submit&act=datasource_remove&idDatasource=".$datasource_obj->id,"btn-danger","Are you sure you want to remove definitively this datasource?");
 // build grid
 $grid=new strGrid();
 // add grid row
 $grid->addRow();
 // renderize description list into grid
 $grid->addCol($form->render(0,6),"col-xs-12");
 // renderize grid into bootstrap sections
 $bootstrap->addSection($grid->render(true,3));
