<link rel="stylesheet" href="style.css">
<h1>MY BLOG!</h1>
<a href='new_post.php'>Crear nuevo post</a>
<style>
details {
	margin: 2em;
	border: 1px solid gray;
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
			echo "<details>";
            		echo "<summary>";
			echo $row['id'] . " :: " . $row['post'] . " :: " . $row['replyto'];
			echo "<details><summary>Reply</summary><form action='?'><input type='hidden' name='replyto' value='" . $row['id'] ."'><input type='text' name='post'><input type='submit'></form></details>";
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
