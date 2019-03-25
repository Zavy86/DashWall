/**
 * DWP DateTime - Script
 *
 * @package DashWall\Plugin\DWP DateTime
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
function dwp_datetime(options){
 // debug
 console.log("dwp_datetime initialization: "+options.uid);
 console.log(options);
 // get and resize canvas
 var canvas=document.getElementById("canvas_"+options.uid);
 canvas.width=canvas.parentNode.clientWidth;
 canvas.height=canvas.parentNode.clientHeight;
 // initial refresh
 refresh();
 // interval refresh
 if(options.refresh>0){setInterval(function(){refresh();},options.refresh);}
 /* refresh function */
 function refresh(){
  $.ajax({
   url:"pfc.php?plugin=dwp_datetime",
   type:"POST",
   data:{
    format:options.format
   },
   cache:false,
   success:function(response){
    // alert if error
    if(response.error){
     console.log("dwp_datetime: "+options.uid);
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
  console.log("dwp_datetime refresh: "+options.uid);
  console.log(response);
  var ctx=canvas.getContext('2d');
  ctx.font = "32px Roboto";
  ctx.fillStyle = options.color;
  ctx.clearRect(0,0,canvas.width,canvas.height)
  ctx.textAlign="center";
  ctx.fillText(response.output,(canvas.width/2),(canvas.height/2));
 }
}
