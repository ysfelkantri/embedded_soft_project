<?php

//Connexion a la base de donnees 
$dbh = new PDO("sqlite:/home/ysf/embedded_soft_project/embedded_soft_project/Client/BD/farm_water.db");

//Requetes de selection de dernier 24 valeurs  
$sql1 = "SELECT * FROM water_level_table WHERE rowid>=(SELECT MAX(rowid)-100  FROM water_level_table);";
$sql2 = " SELECT time_of_launching_pump, COUNT(*) FROM pompe_table WHERE rowid>=(SELECT MAX(rowid)-7  FROM pompe_table) GROUP BY time_of_launching_pump;";
?>

<?php
echo "pump table <br></br>" ;
	foreach ($dbh->query($sql2) as $row){	
    echo nl2br('[new Date("'.$row[0].'"),'.$row[1].'],' );
	}
	
echo "<br></br> water level table <br></br>" ;
	foreach ($dbh->query($sql1) as $row){          	
		echo nl2br('[new Date("'.$row['time_of_picking'].'"),'.$row['water_level'].'],' );
	}
?>



<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);


      function drawChart() {
        var data = google.visualization.arrayToDataTable([
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

        var options = {
          title: 'variation of water quantity',
          curveType: 'function',
          legend: { position: 'bottom' }

        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      

};	
    </script>
  </head>
  <body>
    <div id="curve_chart" style="width: 900px; height: 500px"></div>
  </body>
</html>

<?php
//fermer la base de donnee 
$dbh = null;
?>

