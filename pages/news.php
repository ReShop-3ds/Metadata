<?php
require_once "db.php";
$news = array(
    "news" => array(
        "news_entry" => []
    )
);
$newsq = $db->prepare("SELECT * FROM `news` ORDER BY `id` DESC");
$newsq->execute();
$all = $newsq->fetchAll();
$i = 0;
foreach($all as $news_new){
    $q = $db->prepare("SELECT UNIX_TIMESTAMP(:date)");
    $q->execute([
        "date" => $news_new['timestamp']
    ]);
    $r = $q->fetch();
    $r = $r[0];
    if(is_null($news_new['banner_url'])){
        $news["news"]["news_entry"][] = array(
            "headline" => $news_new['headline'],
            "description" => str_replace("\r", '', $news_new['newscontent']),
            "date" => intval($r),
            "id" => intval($news_new['id']),
        );
    }else{
        $news["news"]["news_entry"][] = array(
            "headline" => $news_new['headline'],
            "description" => '(banner:1)'.str_replace('\r', '', $news_new['newscontent']),
            "date" => intval($r),
            "images" => array(
                "image" => [
                    array(
                        "index" => 1,
                        "type" => "banner",
                        "url" => $news_new['banner_url'],
                        "height" => 126,
                        "width" => 300
                    )
                ]
            ),
            "id" => intval($news_new['id']),
        );
    }
    $i++;
}
$news['news']['length'] = $i;
echo json_encode($news, JSON_UNESCAPED_SLASHES);