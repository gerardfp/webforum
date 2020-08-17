<style>
html {
	font-family: sans;
}

h1 {
	background-color: #e35614;
	color: white;
	padding: 16px;
}

body > details {
	border: 1px solid #e35614;
	margin-left: 0;
}

details {
	margin: 24px 0px 24px 24px;
	border-left: 1px solid #e35614;
}

summary {
	background-color: #fff5ed;
	padding: 16px 16px 0px 16px;
}

.reply summary {
	list-style: none;
	padding: 4px;
	cursor: pointer;
	color: #e35614;
}

.reply {
	border: 0;
	padding: 4px;
	margin: 0;
}

input {
	height: 3em;
}

input[type=submit] {
	color: #e35614;
}
</style>

<h1>&#128024; foro</h1>
<form action="?">
	<input type="text" name="post" size="32">
	<input type="submit" value="Publicar">
</form>

<?php
$mysqli = new mysqli("localhost", "webforum_user", "webforum_password", "webforum_database");

if(isset($_GET['post'])){
	$stmt = $mysqli->prepare("INSERT INTO posts (post, replyto) VALUES (?,NULL)");
	$stmt->bind_param("s", $_GET['post']);
	$stmt->execute();
	$stmt->close();
}

if(isset($_GET['reply'])){
	$stmt = $mysqli->prepare("INSERT INTO posts (post, replyto) VALUES (?,?)");
	$stmt->bind_param("ss", $_GET['reply'], $_GET['replyto']);
	$stmt->execute();
	$stmt->close();
}

$result = $mysqli->query("SELECT * FROM posts");

buildTree($result->fetch_all(MYSQLI_ASSOC));

function buildTree(array $rows, $replyto = NULL) {
    	foreach ($rows as $row) {
        	if ($row['replyto'] == $replyto) {
			echo "
				<details open>
            				<summary>{$row['post']}
						<details class='reply'>
							<summary>&#8617;</summary>
							<form action='?'>
								<input type='hidden' name='replyto' value='{$row['id']}'>
								<input type='text' name='reply' size='32'>
								<input type='submit' value='Responder'>
							</form>
						</details>
					</summary>
			";
			buildTree($rows, $row['id']);
			echo "</details>";
		}
    	}
}
?>
