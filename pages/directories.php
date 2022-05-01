<?php
require_once "db.php";
$directories = array(
    "directories" => array(
        "directory" => []
    )
);
$directoriesq = $db->prepare("SELECT * FROM `directory`");
$directoriesq->execute();
$all = $directoriesq->fetchAll();
$i = 0;
foreach($all as $directory){
    $directories["directories"]["directory"][] = array(
        "name" => $directory['name'],
        "icon_url" => $directory['icon_url'],
        "icon_width" => 128,
        "icon_height" => 96,
        "banner_url" => $directory['banner_url'],
        "index" => intval($directory['index']),
        "id" => intval($directory['id']),
        "type" => $directory['type'],
        "standard" => is_true($directory['standard']),
        "new" => is_true($directory['new'])
    );
    $i++;
}
$directories['directories']['length'] = $i;
$directories['directories']['catalog_id'] = 76106;
echo json_encode($directories, JSON_UNESCAPED_SLASHES);