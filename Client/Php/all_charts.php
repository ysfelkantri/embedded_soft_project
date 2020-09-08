<?php

//Connexion a la base de donnees 
$dbh = new PDO("sqlite:/home/ysf/embedded_soft_project/embedded_soft_project/Client/BD/farm_water.db");

//Requetes de selection de dernier 24 valeurs  
$sql1 = "SELECT * FROM water_level_table WHERE rowid>=(SELECT MAX(rowid)-100  FROM water_level_table);";
$sql2 = " SELECT time_of_launching_pump, COUNT(*) FROM pompe_table WHERE rowid>=(SELECT MAX(rowid)-7  FROM pompe_table) GROUP BY time_of_launching_pump;";
?>

<?php
/*echo "pump table <br></br>" ;
	foreach ($dbh->query($sql2) as $row){	
    echo nl2br('[new Date("'.$row[0].'"),'.$row[1].'],' );
	}
	
echo "<br></br> water level table <br></br>" ;
	foreach ($dbh->query($sql1) as $row){          	
		echo nl2br('[new Date("'.$row['time_of_picking'].'"),'.$row['water_level'].'],' );
	}*/
?>




<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

      // Load Charts and the corechart and barchart packages.
      google.charts.load('current', {'packages':['corechart']});

      // Draw the pie chart and bar chart when Charts is loaded.
      google.charts.setOnLoadCallback(drawChart);
	  
	  function drawChart() {
        var data1 = google.visualization.arrayToDataTable([
    	    ['Time of picking', 'water level','minimal treshold'],
    	    <?php
          	foreach ($dbh->query($sql1) as $row){
          	if ($row['water_level']==NULL){
          		continue;
          	}
          	//to change minimal treshold "80%":
    		echo '[new Date("'.$row['time_of_picking'].'"),'.$row['water_level'].',80],' ;
			}
          	?>        
        ]);
        var data2 = new google.visualization.arrayToDataTable([
          ['Day', 'cunsumption of water in Liters/day'],
          <?php
          	foreach ($dbh->query($sql2) as $row){
          	//to change quantity of water in one pumping time here is "750L"
          	$row[1] *= 750 ;
    		echo '[new Date("'.$row[0].'"),'.$row[1].'],' ;
			}
          	?>
        ]);
        
        var linechart_options = {title:'water level quantity',
        				seriesType: "line",
   	 					series: {
						90: {
							type:"steppedArea",
							color: '#FF0000',
							visibleInLegend: true,
							//areaOpacity: 0,
							enableInteractivity: false
						  }
						},
                     	width:550,
                     	height:400};
        var linechart = new google.visualization.LineChart(document.getElementById('linechart_div'));
       
        var barchart_options = {
          title: 'pump water consumption',
          width: 600,
          series: [{color: 'blue', visibleInLegend: true}, {color: 'red', visibleInLegend: false}],
          //colors: ['red'],
          legend: { position: 'none' },
          chart: { title: 'water pump consumption per day',
                   subtitle: 'pump water consumption' },
          bars: 'horizontal', // Required for Material Bar Charts.
          axes: {
            x: {
              0: { side: 'top', label: 'Percentage'} // Top x-axis.
            }
          },
          bar: { groupWidth: "90%" }
        };
        var barchart = new google.visualization.BarChart(document.getElementById('barchart_div'));
        
        
        linechart.draw(data1, linechart_options);
        barchart.draw(data2, barchart_options);
       	
};

</script>
<body class="bg">
 <div > 
    <!--Table and divs that hold the pie charts-->
    
   <div style="margin-top:13% ;" >
    <table class="columns" align="center" >
      <tr>
        <td style="margin-top:40px ;">
        	<div id="linechart_div" style="border: 1px solid #ccc"></div></td>
        <td 	style="width: 6%;"></td>
        <td>
        	<div id="barchart_div" style="border: 1px solid #ccc"></div>
        	<form method="post">
        	<input type= "submit" name="average" class="button" value="show average" /> 
        	
        	<?php
        	if (isset($_POST["average"])){
        	$sum = 0;
          	$i = 0;
          	foreach ($dbh->query($sql2) as $row){
          	$i++;
          	$sum += $row[1] ;
          	}
        	$average = ($sum/$i)*750 ;
			echo nl2br("<div class=headline> Weekly water consumption average : <span class=subheadline> ".(float)$average."liters/day</span></div>");
        	}
        	?>
        	
        	</td>

      </tr>
    </table>
    </div>
  </body>
</html>


<style>
body, html {
  height: 100%;
  margin: 0;
}

.bg {
  /* The image used */
  background-image: url("farm.png");

  /* Full height */
  height: 100%; 

  /* Center and scale the image nicely */
  background-position: center;
  background-repeat: no-repeat;
  background-size: 100% 100%;
}

.headline{
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size:24px;
 	margin-top: 5px;
 	margin-bottom: 0px;
	font-weight: normal;
	color: #039dfc;
	}
	
.subheadline{
	font-family: "Lucida Grande", Tahoma;
	font-size: 14px;
	font-weight: lighter;
	font-variant: normal;
	text-transform: uppercase;
	color: #FFFFFF;
	margin-top: 10px;
	text-align: center ;
	letter-spacing: 0.3em;
	}
	
.button {
  background-color: #66b3ff;
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}
</style>

<?php
//fermer la base de donnee 
$dbh = null;
?>
