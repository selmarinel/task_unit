<?php

require_once "vendor/autoload.php";
$service = new SaverFile($_POST['url']);
$service->saveByUrl();

?>
<form method="post">
    <input type="text" name="url" value="<?=$_POST['url']?>">
    <input type="submit">
</form>
<img src="<?= $service->getFileName() ?>" width="250px">
