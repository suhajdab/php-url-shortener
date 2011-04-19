<?

	require('inc/config.php');

	$id = $_GET['id'];
	$db = new mysqli(MYSQLI_HOST, MYSQLI_USER, MYSQLI_PASSWORD, MYSQLI_DATABASE)
		or die('mysql error');
	$db->query('SET NAMES "utf8"');
	//	stats for slug
	if (is_numeric($_GET['id'])) {
		$id = $db->real_escape_string($id);
		$result = $db->query('SELECT DATE(`date`) AS day, COUNT( * ) as hits FROM `hits` WHERE `id`=' . $id . ' GROUP BY day ORDER BY day ASC');
		if ($result && $result->num_rows > 0 ) {
			$hits = '[';
			while ( $row = $result->fetch_object() ) {
				$hits .= '[Date.parse("' . $row->day . '"),' . $row->hits . '],';
			}
			$hits .= ']';
		} else {
			$error = "No data available!";
		}
	}
?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>url shortener - stats <?= $id ?> :: onereason</title>
		<script type="text/javascript">
			var serie = {
				type: 'area',
				name: 'hits',
				data: <?= $hits ?>
			};
		</script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		<script type="text/javascript" src="js/highcharts.js"></script>
		<script type="text/javascript" src="js/themes/gray.js"></script>
		<script type="text/javascript">

			var chart;
			$(document).ready(function() {
				chart = new Highcharts.Chart({
					chart: {
						renderTo: 'container',
						zoomType: 'x',
						spacingRight: 20
					},
				    title: {
						text: 'Analytics'
					},
					xAxis: {
						type: 'datetime',
						dateTimeLabelFormats: {
				            month: '%e. %b',
				            year: '%b'
				         },
						tickInterval: 24*3600*1000
					},
					yAxis: {
						title: {
							text: 'Hits per day'
						},
						min: 0
					},
					tooltip: {
						shared: true					
					},
					legend: {
						enabled: false
					},
					plotOptions: {
						area: {
							fillColor: {
								linearGradient: [0, 0, 0, 300],
								stops: [
									[0, 'rgb(140,198,63)'],
									[1, 'rgba(2,0,0,0)']
								]
							},
							lineWidth: 1,
							marker: {
								enabled: false,
								states: {
									hover: {
										enabled: true,
										radius: 5
									}
								}
							},
							shadow: false,
							states: {
								hover: {
									lineWidth: 1						
								}
							}
						}
					},

					series: [serie]
				});


			});

		</script>
	</head>
	<body>
		<a href="stats-overview.php">to overview</a>
		<div id="container" style="width: 600px; height: 200px; margin: 0 auto">
			<? if (isset($error)) echo $error; ?>
		</div>
	</body>
</html>