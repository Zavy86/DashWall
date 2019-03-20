/**
 * DWP Trend - Script
 *
 * @package DashWall\Plugin\DWP Trend
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
function dwp_trend(options){
 // debug
 console.log("dwp_trend initialization: "+options.uid);
 console.log(options);

 /* initializations */
 var ctx=document.getElementById("canvas_"+options.uid).getContext('2d')
 var chart_data;
 var chart_options={
  /*animation:{duration:0},*/
  responsive: true,
  maintainAspectRatio: false,
  legend:{display:false}
 };
 var myChart=new Chart(ctx,{type:'line',options:chart_options});

 /* initial refresh */
 refresh();

 /* interval refresh */
 if(options.refresh>0){setInterval(function(){refresh();},options.refresh);}

 /* refresh function */
 function refresh(){
  $.ajax({
   url:"pfc.php?plugin=dwp_trend",
   type:"POST",
   data:{values:options.values},
   cache:false,
   success:function(response){
    // alert if error
    if(response.error){
     console.log("dwp_trend: "+options.uid);
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
  console.log("dwp_trend refresh: "+options.uid);
  console.log(response);
  // build chart data
  chart_data={
   labels:response.output.row,
   datasets:[
    {
     label:'Min',
     data:response.output.min,
     backgroundColor:'rgba(54,162,235,0.2)',
     borderColor:'rgba(54,162,235,0.2)',
     borderWidth:1,
     pointRadius:0,
     fill:false
    },
    {
     label:'Max',
     data:response.output.max,
     backgroundColor:'rgba(54,162,235,0.2)',
     borderColor:'rgba(54,162,235,0.2)',
     borderWidth:1,
     pointRadius:0,
     fill:'-1'
    },
    {
     label:'Avg',
     data: response.output.avg,
     backgroundColor:'rgba(54,162,235,0.2)',
     borderColor:'rgba(54,162,235,1)',
     borderWidth:2,
     fill:false
    }
   ]
  };
  // overwrite chart data
  myChart.data=chart_data;
  // update chart
  myChart.update();
 }

}
