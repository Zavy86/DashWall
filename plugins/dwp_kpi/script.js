/**
 * DWP KPI - Script
 *
 * @package DashWall\Plugin\DWP KPI
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
function dwp_kpi(options){

 console.log("kpi: "+options.uid);
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
   url:"pfc.php?plugin=dwp_kpi",
   type:"POST",
   data:{
    format:options.uid
   },
   cache:false,
   success:function(response){
    // alert if error
    if(response.error){
     console.log("dwp_kpi: "+options.uid);
     console.error(response);
    }else{
     update(response);
    }
   },
   error:function(XMLHttpRequest,textStatus,errorThrown){
    // alert
    console.error(errorThrown);
   }
  });
 }

 /* update function */
 function update(response){
  var ctx=canvas.getContext('2d');
  ctx.fillStyle = options.color;
  ctx.clearRect(0,0,canvas.width,canvas.height)
  ctx.textAlign="center";
  ctx.font = "64px Roboto";
  ctx.fillText(response.output.value,(canvas.width/2),(canvas.height/2));
  ctx.font = "16px Roboto";
  ctx.fillText(response.output.description,(canvas.width/2),(canvas.height/2)+32);
 }

}
