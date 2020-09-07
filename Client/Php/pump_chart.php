<?php

//Connexion a la base de donnees 
$dbh = new PDO("sqlite:/home/ysf/embedded_soft_project/embedded_soft_project/Client/BD/farm_water.db");

//Requetes de selection de dernier 24 valeurs  
$sql2 = " SELECT time_of_launching_pump, COUNT(*) FROM pompe_table WHERE rowid>=(SELECT MAX(rowid)-7  FROM pompe_table) GROUP BY time_of_launching_pump;";
?>

<?php
          	foreach ($dbh->query($sql2) as $row){	
    		echo nl2br('[new Date("'.$row[0].'"),'.$row[1].'],' );
			}
          	?>

<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawStuff);

      function drawStuff() {
        var data = new google.visualization.arrayToDataTable([
          ['Opening Move', 'Percentage'],
          <?php
          	foreach ($dbh->query($sql2) as $row){
    		echo '[new Date("'.$row[0].'"),'.$row[1].'],' ;
			}
          	?>
        ]);

        var options = {
          title: 'Chess opening moves',
          width: 900,
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

        var chart = new google.charts.Bar(document.getElementById('top_x_div'));
        chart.draw(data, options);
      };
    </script>
  </head>
  <body>
    <div id="top_x_div" style="width: 900px; height: 500px;"></div>
  </body>
</html>

<?php
//fermer la base de donnee 
$dbh = null;
?>

