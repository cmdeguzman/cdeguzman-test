<?php 

require_once("classes/app.php");
require_once("classes/image.php");

$imageId = isset($_POST['imageId']) ? $_POST['imageId'] : null;
$action = isset($_POST['action']) ? $_POST['action'] : null;

$app = new App($imageId, $action);
$app->execute();

?>
