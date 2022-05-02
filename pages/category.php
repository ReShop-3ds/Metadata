<?php
require_once "db.php";
$q1 = $db->prepare("SELECT * FROM `directory` WHERE `id`=:id");
$q1->execute([
    "id" => $st
]);
$r = $q1->fetch();
if(!$r){
    echo "no";
    exit;
}
$q2 = $db->prepare("SELECT * FROM `title` WHERE `category_id`=:category_id ORDER BY `id` DESC");
$q2->execute([
    "category_id" => $st
]);
$r2 = $q2->fetchAll();
$dir = array(
    "directory" => array(
        "name" => $r['name'],
        "icon_url"=> $r['icon_url'],
		"icon_width" => 76,
		"icon_height" => 58,
		"banner_url" => $r['banner_url'],
		"contents" => array(
		    "content" => []
		)
    )
);
$i = 0;
foreach($r2 as $title){
    $tmstp = $db->prepare("SELECT UNIX_TIMESTAMP(:date)");
    $tmstp->execute([
        "date" => $title['release_date']
    ]);
    $tmstp = $tmstp->fetch();
    $tmstp = $tmstp[0];
    $i++;
    $dir["directory"]["contents"]["content"][] = array(
        "title" => array(
            "publisher" => array(
                "name" => $title['publisher_name'],
                "id" => rand(1, 1000)
            ),
            "display_genre" => "Homebrew",
            "release_date_on_eshop" => date("Y\-m\-d", $tmstp),
            "retail_sales" => false,
            "eshop_sales" => true,
            "demo_available" => false,
            "aoc_available" => false,
            "in_app_purchase" => false,
            "name" => $title['name'],
            "id" => intval($title['id']),
            "product_code" => $title['product_code'],
            "icon_url" => $title['icon_url'],
            "banner_url" => $title['banner_url'],
            "new" => is_true($title['new'])
        ),
        "index" => $i
    );
}
$dir["directory"]["contents"]['length'] = $i;
$dir["directory"]["contents"]['offset'] = 0;
$dir["directory"]["contents"]['total'] = count($r2);
$dir["directory"]["id"] = $st;
$dir["directory"]["type"] = "search";
$dir["directory"]["component"] = "title";
echo json_encode($dir, JSON_UNESCAPED_SLASHES);