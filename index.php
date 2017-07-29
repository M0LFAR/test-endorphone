<?php
ini_set('display_errors', 0);
require_once(__DIR__.'/PoolPhone.php');
$configDd = require_once (__DIR__.'/config/db_conf.php');
$pool = PoolPhone::getInstance($configDd);
$brandsLine = $_GET['brands'];

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) ) {
    // якщо це Ajax запит, результат пакується в json і відправляється у відповідь без вмісту html
    // в даному випадку виклик $pool->$brandsLine тотожний  $pool->getDeviceForBrends($brandsLine);
    echo json_encode($pool->$brandsLine);
    exit;
}else{
    $devices = $pool->getDeviceForBrends($brandsLine);
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>test</title>
	<link rel="icon" sizes="192x192" href="http://endorphone.com.ua/favico_logo2_192x192.png">
	<link rel="stylesheet" href="assets/css/main.css">
	<link href="http://fonts.googleapis.com/css?family=Roboto:500,100,300,400,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
</head>
<body>
	<header>
		<h1>Тестове завдання</h1>
	</header>
	<menu>
		<div id="ck-button">
            <?php foreach ($pool->brends as $brendName=>$id):?>
   		<label>
      		<input type="checkbox" name="<?=$brendName?>" value="<?=$id?>" <?=key_exists($id, $devices)?'checked':''?>><span><?=$brendName?></span>
   		</label>
            <?php endforeach;?>
		</div>
	</menu>
	<section id="content">
        <?php foreach ($devices as $idBreand=>$deviceFeed):?>
            <?php $devicesList = ''; foreach ($deviceFeed as $device){
                $devicesList.="<li>{$device['name']}</li>";
            }?>
                <ul id="<?=$idBreand?>">
                <?=$devicesList?>
            </ul>
        <?php endforeach;?>
	</section >
<script type="text/javascript" src="assets/js/script.js"></script>
</body>
</html>