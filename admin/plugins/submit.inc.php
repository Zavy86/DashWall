<?php
/**
 * Submit
 *
 * @package DashWall\Admin\Plugins
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
 checkAuthorizations();
 // switch action
 switch(ACTION){
  // plugins
  case "plugin_add":plugin_add();break;
  case "plugin_file_save":plugin_file_save();break;
  case "plugin_file_remove":plugin_file_remove();break;
  // default
  default:
   api_alert("Submit function for action <em>".ACTION."</em> was not found in module <em>".MODULE."</em>..","danger");
   api_redirect("admin.php?mod=".MODULE);
 }

 /**
  * Plugin Add
  */
 function plugin_add(){
  api_dump($_REQUEST,"_REQUEST");
  // check authorizations
  checkAuthorizations();
  // acquire variables
  $r_plugin=preg_replace('/[^a-z0-9\-._]/','',str_replace(" ","_",$_REQUEST['plugin']));
  // check for duplicated
  if(is_dir(DIR."plugins/".$r_plugin)){api_alert("Plugin already exists","danger");api_redirect("admin.php?mod=".MODULE."&scr=plugin_list");}
  // build functions
  $index=<<<EOS
<?php header("location: ../index.php"); ?>
EOS;
  // build functions
  $functions=<<<EOS
<?php
/**
 * $r_plugin - Functions
 *
 * @package DashWall\Plugin\\$r_plugin
 * @author  Author <author@mail.tld>
 * @link    https://author.web
 */
function pfc(&\$return){
 // errors
 if(0){
  \$return->error=true;
  \$return->errors[]="error_description";
  return;
 }
 // set output and return
 \$return->output="output_datas";
 return;
}
?>
EOS;
  // build update
  $update=<<<EOS
<?php
/**
 * $r_plugin - Update Dataset
 *
 * @package DashWall\Plugin\\$r_plugin
 * @author  Author <author@mail.tld>
 * @link    https://author.web
 */
function pud(&\$return){
 // errors
 if(0){
  \$return->error=true;
  \$return->errors[]="error_description";
  return;
 }
 // set output and return
 \$return->output="output_result";
 return;
}
?>
EOS;
  // build script
  $script=<<<EOS
/**
 * $r_plugin - Script
 *
 * @package DashWall\Plugin\\$r_plugin
 * @author  Author <author@mail.tld>
 * @link    https://author.web
 */

function $r_plugin(options){

 console.log("$r_plugin initialization: "+options.uid);
 console.log(options);

 /* get and resize canvas */
 var canvas=document.getElementById("canvas_"+options.uid);
 canvas.width=canvas.parentNode.clientWidth;
 canvas.height=canvas.parentNode.clientHeight;

 /* initial refresh */
 refresh();

 /* interval refresh */
 if(options.refresh>0){setInterval(function(){refresh();},options.refresh);}

 /* refresh function */
 function refresh(){
  $.ajax({
   url:"pfc.php?plugin=$r_plugin",
   type:"POST",
   data:{
    uid:options.uid
   },
   cache:false,
   success:function(response){
    // alert if error
    if(response.error){
     console.log("$r_plugin: "+options.uid);
     console.error(response);
    }else{
     update(response);
    }
   },
   error:function(XMLHttpRequest,textStatus,errorThrown){console.error(errorThrown);}
  });
 }

 /* update function */
 function update(response){
  // debug
  console.log("$r_plugin refresh: "+options.uid);
  console.log(response);

  var ctx=canvas.getContext('2d');
  ctx.font = "32px Roboto";
  ctx.fillStyle = options.color;

  ctx.clearRect(0,0,canvas.width,canvas.height)

  ctx.textAlign="center";
  ctx.fillText(response.output,(canvas.width/2),(canvas.height/2));
 }

}
EOS;
  // write plugin files
  if(!is_dir(DIR."plugins/".$r_plugin)){mkdir(DIR."plugins/".$r_plugin);}
  file_put_contents(DIR."plugins/".$r_plugin."/index.php",$index);
  file_put_contents(DIR."plugins/".$r_plugin."/functions.php",$functions);
  file_put_contents(DIR."plugins/".$r_plugin."/update.php",$update);
  file_put_contents(DIR."plugins/".$r_plugin."/script.js",$script);
  // redirect
  api_alert("Plugin created","success");
  api_redirect("admin.php?mod=".MODULE."&scr=plugin_view&plugin=".$r_plugin);
 }

 /**
  * Plugin File Save
  */
 function plugin_file_save(){
  // acquire source
  $r_source=htmlspecialchars_decode($_REQUEST['source']);
  // unset source and debug
  unset($_REQUEST['source']);
  api_dump($_REQUEST,"_REQUEST");
  // check authorizations
  checkAuthorizations();
  // acquire variables
  $r_plugin=$_REQUEST['plugin'];
  $r_file=$_REQUEST['file'];
  $r_filename=preg_replace('/[^a-z0-9\-._]/','',str_replace(" ","_",$_REQUEST['filename']));
  // checks
  if(!is_dir(DIR."plugins/".$r_plugin)){api_alert("Plugin not found","danger");api_redirect("admin.php?mod=".MODULE."&scr=plugin_list");}
  if($r_file && !file_exists(DIR."plugins/".$r_plugin."/".$r_file)){api_alert("File not found","danger");api_redirect("admin.php?mod=".MODULE."&scr=plugin_view&plugin=".$r_plugin);}
  // debug
  api_dump($r_source,"source");
  // check for file
  if(!$r_file){$r_file=$r_filename;}
  // backup previous version                                                      /** @todo */
  // store source
  $bytes=file_put_contents(DIR."plugins/".$r_plugin."/".$r_file,$r_source);
  // check write
  if($bytes!==false){api_alert("Plugin file updated","success");}else{api_alert("Error updating plugin file","danger");}
  // check for new name
  if($r_filename!=$r_file){rename(DIR."plugins/".$r_plugin."/".$r_file,DIR."plugins/".$r_plugin."/".$r_filename);}
  // redirect
  api_redirect("admin.php?mod=".MODULE."&scr=plugin_view&plugin=".$r_plugin."&file=".$r_filename);
 }

 /**
  * Plugin File Remove
  */
 function plugin_file_remove(){
  api_dump($_REQUEST,"_REQUEST");
  // check authorizations
  checkAuthorizations();
  // acquire variables
  $r_plugin=$_REQUEST['plugin'];
  $r_file=$_REQUEST['file'];
  // checks
  if(!is_dir(DIR."plugins/".$r_plugin)){api_alert("Plugin not found","danger");api_redirect("admin.php?mod=".MODULE."&scr=plugin_list");}
  if(!file_exists(DIR."plugins/".$r_plugin."/".$r_file)){api_alert("File not found","danger");api_redirect("admin.php?mod=".MODULE."&scr=plugin_view&plugin=".$r_plugin);}
  // delete file
  unlink(DIR."plugins/".$r_plugin."/".$r_file);
  // alert and redirect
  api_alert("Plugin file removed","warning");
  api_redirect("admin.php?mod=".MODULE."&scr=plugin_view&plugin=".$r_plugin);
 }
