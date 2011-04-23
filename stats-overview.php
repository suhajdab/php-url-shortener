<?

	require('inc/config.php');

	$db = new mysqli(MYSQLI_HOST, MYSQLI_USER, MYSQLI_PASSWORD, MYSQLI_DATABASE);
	$db->query('SET NAMES "utf8"');
	$result = $db->query('SELECT redirect.*, DATE(redirect.date) as shortdate, (COUNT(hits.id) + redirect.hits) as `sum` FROM `redirect` LEFT JOIN `hits` ON redirect.id  = hits.id GROUP BY redirect.id ORDER BY redirect.id DESC');
?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>1r.nu url shortener - stat overview :: onereason</title>
		<style>
			table {
				border: 1px solid rgb(140,198,63);
				border-collapse: collapse;
				border-spacing: 0;
			}
			
			caption {
				padding: 5px;
				background: rgb(140,198,63);
				color: #fdfdfd;
				text-transform: uppercase;
			}
			
			thead {
				background: #ddd;
				font-size: 80%;
				text-transform: uppercase;
			}
			
			tbody tr:nth-child(even) {
				background: #ededed;
			}
			
			tbody tr:hover {
				background: rgb(140,198,63);
			}

			td {
				padding: 3px 8px;
			}
			
			tbody td:first-child {
				white-space: nowrap;
			}
			
			tbody td:nth-child(3) {
				max-width: 500px;
				font-size: 80%;
				word-wrap: break-word;
			}
			
			a {
				color: #333;
			}
		</style>
	</head>
	<body>
		<table>
			<caption>url shortener overview</caption>
			<thead>
				<tr>
					<td>added</td>
					<td>slug</td>
					<td>url</td>
					<td>hits</td>
					<td></td>
				</tr>
			</thead>
			<tbody>
			<? while($row = $result->fetch_object()) { ?>
				<tr>
					<td><?= $row->shortdate ?></td>
					<td><?= $row->slug ?></td>
					<td><a href="<?= $row->url ?>"><?= $row->url ?></a></td>
					<td><?= $row->sum ?></td>
					<td>
						<a href="stats.php?id=<?= $row->id ?>">details</a>
					</td>
				</tr>
			<? } ?>
			</tbody>
		</table>
	</body>
</html>