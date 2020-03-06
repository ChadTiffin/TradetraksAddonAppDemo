<?php
//Load the HTTP request class
include("Request.class.php");

//API endpoint we're going to use
$url = "https://services.trade-traks.ca/api/Equipment/get";

//API token gets passed in to the App url automatically by tradetraks
$apiToken = $_GET['token'];

//Make the HTTP request
$response = Request::get($url,[
	'token' 	=> $apiToken,
	'limit' 	=> 5
]);

//Response comes back as JSON, so decode it into a PHP object
$decodedResponse = json_decode($response);

//Actual data is contained in the data property
$equipment = $decodedResponse->data;

//Now we can render our HTML:
?>
<!DOCTYPE html>
<html>
<head>
	<title>Addon Demo App</title>
</head>
<body>
	<div class="container-fluid">
		<?php if ($equipment): ?>
			<p><?=count($equipment)?> results</p>
			<table class="table table-sm">
				<thead>
					<th>Description</th>
					<th>Next Service Date</th>
					<th>Goto</th>
				</thead>
				<tbody>
					<?php foreach ($equipment as $eq): ?>
					
					<tr>
						<td><?=$eq->description?></td>
						<td><?=$eq->next_service_date?></td>
						<!-- We can use window.top.location.href to force the main window to redirect, rather than just the page in the iframe -->
						<td><button onclick="window.top.location.href='https://tradetraks.app/equipment/list/<?=$eq->slug?>'">Open</button></td>
					</tr>
					
					<?php endforeach; ?>
				</tbody>
			</table>

		<?php endif; ?>
	</div>
</body>
</html>
