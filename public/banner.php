<?php

require_once '../autoload.php';

$db = Mysql::getInstance();
$visitor = new Visitor();

$row = $db->selectOne($visitor::Table, '*', [
    'ip_address =' => $visitor->getIp(),
    'user_agent =' => $visitor->getUserAgent(),
    'page_url =' => $visitor->getPageUrl()
]);

if(empty($row)){
    $db->insertOne($visitor::Table, $visitor::Rows, [
        $visitor->getIp(),
        $visitor->getUserAgent(),
        $visitor->getViewDate(),
        $visitor->getPageUrl(),
        $visitor->getViewsCount()
    ]);
}else{
    $db->update($visitor::Table, [
        'view_date' => $visitor->getViewDate(),
        'views_count' => ($row['views_count'] + 1)
    ], ['id =' => $row['id']]);
}

echo file_get_contents('../img.png');
