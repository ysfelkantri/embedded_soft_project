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
    	    ['Time of picking', 'water level'],
    	    <?php
          	foreach ($dbh->query($sql1) as $row){
          	if ($row['water_level']==NULL){
          		continue;
          	}
    		echo '[new Date("'.$row['time_of_picking'].'"),'.$row['water_level'].'],' ;
			}
          	?>        
        ]);
        var data2 = new google.visualization.arrayToDataTable([
          ['Day', 'times of launching pump'],
          <?php
          	foreach ($dbh->query($sql2) as $row){
    		echo '[new Date("'.$row[0].'"),'.$row[1].'],' ;
			}
          	?>
        ]);
        
        var linechart_options = {title:'line Chart: water level quantity',
                       width:500,
                       height:400};
        var linechart = new google.visualization.LineChart(document.getElementById('linechart_div'));
       
        var barchart_options = {
          title: 'Chess opening moves',
          width: 500,
          series: [{color: 'blue', visibleInLegend: true}, {color: 'red', visibleInLegend: false}],
          //colors: ['red'],
          legend: { position: 'none' },
          chart: { title: 'Chess opening moves',
                   subtitle: 'popularity by percentage' },
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
    
   <div style="margin-top:8% ;" >
    <table class="columns" align="center" >
      <tr>
        <td style="margin-top:40px ;">
        	<div id="linechart_div" style="border: 1px solid #ccc"></div></td>
        <td 	style="width: 10%;"></td>
        <td>
        	<div id="barchart_div" style="border: 1px solid #ccc"></div></td>
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
  background-image: url("cows.jpg");

  /* Full height */
  height: 100%; 

  /* Center and scale the image nicely */
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
}

<?php
//fermer la base de donnee 
$dbh = null;
?>
