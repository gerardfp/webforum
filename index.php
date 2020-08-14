<h1>FORUM</h1>
<style>
html {
	font-family: sans;
}

details {
	margin: 24px;
	border-left: 1px solid blue;
}

summary {
	background-color: #e1e1e1;
	padding: 16px;
}

.reply summary {
	list-style: none;
	list-style-image: none; //url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZlcnNpb249IjEuMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj48cGF0aCBkPSJNOTAuNiwySDkuNGMtMS4zLDAtMi4zLDEtMi4zLDIuM1Y3NmMwLDEuMywxLDIuMywyLjMsMi4zaDI2LjFMNTAsOThsMTQuNS0xOS43aDI2LjFjMS4zLDAsMi4zLTEsMi4zLTIuM1Y0LjMgIEM5Mi45LDMuMSw5MS45LDIsOTAuNiwyeiBNNzIuMSw0NS4xbC00LjUsMGwtMjAuOSwwbDguNiw4LjdsLTcsNi45TDM0LjEsNDYuNGwtNi4yLTYuMmwwLDBsMCwwbDEuOC0xLjhsNS4yLTUuMWwwLDBsNy4zLTcuMyAgbDYuMi02LjJsNi45LDdsLTYuMiw2LjJsLTIuNCwyLjRsMjAuOCwwbDQuNiwwTDcyLjEsNDUuMXoiLz48L3N2Zz4K");
	padding: 4px;
	cursor: pointer;
}

.reply {
	border: 0;
	padding: 4px;
	margin: 0px;
}

input {
	height: 3em;
}
</style>

<?php
$mysqli = new mysqli("localhost", "webforum_user", "webforum_password", "webforum_database");

if(isset($_GET['post'])){
	$stmt = $mysqli->prepare("INSERT INTO posts (post, replyto) VALUES (?,?)");

	$stmt->bind_param("ss", $_GET['post'], $_GET['replyto']);

	$stmt->execute();
	$stmt->close();

}

function buildTree(array $rows, $replyto = NULL) {
    	$branch = array();

    	foreach ($rows as $row) {
        	if ($row['replyto'] == $replyto) {
			echo "<details open>";
            		echo "<summary>";
			echo $row['id'] . " :: " . $row['post'] . " :: " . $row['replyto'];
			echo "<details class='reply'><summary>Responder</summary><form action='?'><input type='hidden' name='replyto' value='" . $row['id'] ."'><input type='text' name='post'><input type='submit' value='Responder'></form></details>";
			echo "</summary>";
			$children = buildTree($rows, $row['id']);
			echo "</details>";
            		if ($children) {
                		$row['children'] = $children;
            		}
            		$branch[] = $row;
		}
    	}

    	return $branch;
}

$res = $mysqli->query("SELECT * FROM posts");

while($row = $res->fetch_assoc()){
	$rows[] = $row;
}

buildTree($rows);
?>
