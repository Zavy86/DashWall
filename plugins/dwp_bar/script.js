/**
 * DWP Bar - Script
 *
 * @package DashWall\Plugin\DWP Bar
 * @author  Manuel Zavatta <manuel.zavatta@gmail.com>
 * @link    https://github.com/Zavy86/dashwall
 */
function dwp_bar(options){
 // debug
 console.log("dwp_bar initialization: "+options.uid);
 console.log(options);
 // initializations
 var ctx=document.getElementById("canvas_"+options.uid).getContext('2d')
 var chart_data;
 var chart_options={
  /*animation:{duration:0},*/
  responsive: true,
  maintainAspectRatio: false,
  legend:{display:false}
 };
 // build chart
 var myChart=new Chart(ctx,{type:'bar',options:chart_options});
 // initial refresh
 refresh();
 // interval refresh
 if(options.refresh>0){setInterval(function(){refresh();},options.refresh);}
 /* refresh function */
 function refresh(){
  $.ajax({
   url:"pfc.php?plugin=dwp_bars",
   type:"POST",
   data:{values:options.values},
   cache:false,
   success:function(response){
    // alert if error
    if(response.error){
     console.log("dwp_bars: "+options.uid);
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
  console.log("dwp_bars refresh: "+options.uid);
  console.log(response);
  // build chart data
  chart_data={
   labels:response.output.row,
   datasets:[
    {
     label:'Value',
     data: response.output.value,
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
