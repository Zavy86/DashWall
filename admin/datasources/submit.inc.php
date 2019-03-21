<?php
/**
 * Submit
 *
 * @package DashWall\Admin\Datasources
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 checkAuthorizations();
 // switch action
 switch(ACTION){
  // datasources
  case "datasource_save":datasource_save();break;
  case "datasource_test":datasource_test();break;
  case "datasource_remove":datasource_remove();break;
  // default
  default:
   api_alert("Submit function for action <em>".ACTION."</em> was not found in module <em>".MODULE."</em>..","danger");
   api_redirect("admin.php?mod=".MODULE);
 }

 /**
  * Datasource Save
  */
 function datasource_save(){
  api_dump($_REQUEST,"_REQUEST");
  // check authorizations
  checkAuthorizations();
  // get objects
  $datasource_obj=new Datasource($_REQUEST['idDatasource']);
  api_dump($datasource_obj,"datasource object");
  // build query object
  $datasource_qobj=new stdClass();
  $datasource_qobj->id=$datasource_obj->id;
  $datasource_qobj->code=addslashes($_REQUEST['code']);
  $datasource_qobj->description=addslashes($_REQUEST['description']);
  $datasource_qobj->hostname=addslashes($_REQUEST['hostname']);
  $datasource_qobj->connector=addslashes($_REQUEST['connector']);
  $datasource_qobj->database=addslashes($_REQUEST['database']);
  $datasource_qobj->username=addslashes($_REQUEST['username']);
  $datasource_qobj->password=addslashes($_REQUEST['password']);
  $datasource_qobj->tns=addslashes($_REQUEST['tns']);
  $datasource_qobj->queries=addslashes($_REQUEST['queries']);
  // debug
  api_dump($datasource_qobj,"datasource query object");
  // check object
  if($datasource_obj->id){
   // update
   $GLOBALS['DB']->queryUpdate("dashwall__datasources",$datasource_qobj);
   // alert
   api_alert("Datasource updated","success");
  }else{
   // insert
   $datasource_qobj->id=$GLOBALS['DB']->queryInsert("dashwall__datasources",$datasource_qobj);
   // alert
   api_alert("Datasource created","success");
  }
  // redirect
  api_redirect("admin.php?mod=".MODULE."&scr=".api_return_script("datasource_view")."&idDatasource=".$datasource_qobj->id);
 }

 /**
  * Datasource Test
  */
 function datasource_test(){
  api_dump($_REQUEST,"_REQUEST");
  // check authorizations
  checkAuthorizations();
  // get objects
  $datasource_obj=new Datasource($_REQUEST['idDatasource']);
  api_dump($datasource_obj,"datasource object");
  // check object
  if(!$datasource_obj->id){api_alert("Datasource not found","danger");api_redirect("admin.php?mod=".MODULE."&scr=datasource_list");}
  // test connection with a dummy query
  $results=$datasource_obj->query("SELECT 1 FROM DUAL");
  // debug
  api_dump($results,"results");
  // check results
  if(count($results)){api_alert("Datasource test successful","success");}
  else{api_alert("Datasource test failed","warning");}
  // redirect
  api_redirect("admin.php?mod=".MODULE."&scr=".api_return_script("datasource_view")."&idDatasource=".$datasource_obj->id);
 }


 /**
  * Datasource Remove
  */
 function datasource_remove(){
  api_dump($_REQUEST,"_REQUEST");
  // check authorizations
  checkAuthorizations();
  // get objects
  $datasource_obj=new Datasource($_REQUEST['idDatasource']);
  api_dump($datasource_obj,"datasource object");
  // check object
  if(!$datasource_obj->id){api_alert("Datasource not found","danger");api_redirect("admin.php?mod=".MODULE."&scr=datasource_list");}
  // remove division
  $deleted=$GLOBALS['DB']->queryDelete("dashwall__datasources",$datasource_obj->id);
  // check query result
  if(!$deleted){api_alert("An error has occurred","danger");api_redirect("admin.php?mod=".MODULE."&scr=datasource_list&idDatasource=".$datasource_obj->id);}
  // alert and redirect
  api_alert("Datasource removed","warning");
  api_redirect("admin.php?mod=".MODULE."&scr=datasource_list");
 }
