<?php
/**
 * Submit
 *
 * @package DashWall\Admin\Datasets
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 checkAuthorizations();
 // switch action
 switch(ACTION){
  // datasets
  case "dataset_save":dataset_save();break;
  case "dataset_remove":dataset_remove();break;
  // default
  default:
   api_alert("Submit function for action <em>".ACTION."</em> was not found in module <em>".MODULE."</em>..","danger");
   api_redirect("admin.php?mod=".MODULE);
 }

 /**
  * Dataset Save
  */
 function dataset_save(){
  api_dump($_REQUEST,"_REQUEST");
  // check authorizations
  checkAuthorizations();
  // acquire variables
  $r_dataset=$_REQUEST['dataset'];
  $r_id=$_REQUEST['id'];
  // set database
  $database=$GLOBALS['APP']->db;
  // structures query
  $fields_query=<<<EOS
SELECT
 `COLUMN_NAME` AS `field`,
 `DATA_TYPE` AS `typology`
FROM `INFORMATION_SCHEMA`.`COLUMNS`
WHERE `TABLE_SCHEMA`='$database'
 AND `TABLE_NAME` LIKE '$r_dataset'
ORDER BY `ORDINAL_POSITION`
EOS;
  // get fields
  $fields=$GLOBALS['DB']->queryObjects($fields_query);
  // build query object
  $dataset_qobj=new stdClass();
  $dataset_qobj->id=$r_id;
  // cycle all fields
  foreach($fields as $field_f){
   if($field_f->field=="id"){continue;}
   $dataset_qobj->{$field_f->field}=$_REQUEST[$field_f->field];
  }
  // debug
  api_dump($dataset_qobj,"dataset query object");
  // check object
  if($r_id){
   // update
   if($GLOBALS['DB']->queryUpdate($r_dataset,$dataset_qobj)){api_alert("Dataset element updated","success");}
	 else{api_alert("Dataset element updating error","danger");}
  }else{
   // insert
   $dataset_qobj->id=$GLOBALS['DB']->queryInsert($r_dataset,$dataset_qobj);
   // alert
   if($dataset_qobj->id){api_alert("Dataset element created","success");}
	 else{api_alert("Dataset element creating error","danger");}
  }
  // redirect
  api_redirect("admin.php?mod=".MODULE."&scr=".api_return_script("dataset_view")."&dataset=".$r_dataset."&id=".$dataset_qobj->id);
 }

 /**
  * Dataset Remove
  */
 function dataset_remove(){
  api_dump($_REQUEST,"_REQUEST");
  // check authorizations
  checkAuthorizations();
  // acquire variables
  $r_dataset=$_REQUEST['dataset'];
  $r_id=$_REQUEST['id'];
  // remove division
  $deleted=$GLOBALS['DB']->queryDelete($r_dataset,$r_id);
  // check query result
  if(!$deleted){api_alert("An error has occurred","danger");api_redirect("admin.php?mod=".MODULE."&scr=dataset_view&dataset=".$r_dataset);}
  // alert and redirect
  api_alert("Dataset element removed","warning");
  api_redirect("admin.php?mod=".MODULE."&scr=dataset_view&dataset=".$r_dataset);
 }
