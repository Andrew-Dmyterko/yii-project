<?php
/**
 * Автоприсвоение жанра фильма
 * у нас есть уже набранные статьи
 * и находим по контексту жанр или несколько жанров
 * и апдейтим в базу
 * для будущего фильтра
 */


$params = [
    'host' => '127.0.0.1',
    'user' => 'root',
    'password' => '650351',
    'database' => 'blog_db'
];

$genres = [
    1 => 'украинский',
    2 => 'аниме',
    3 => 'биография',
    4 => 'боевик',
    5 => 'вестерн',
    6 => 'военный',
    7 => 'детектив',
    8 => 'детский',
    9 => 'для_взрослых',
    10 => 'документальный',
    11 => 'драма',
    12 => 'история',
    13 => 'комедия',
    14 => 'короткометражка',
    15 => 'концерт',
    16 => 'криминал',
    17 => 'мелодрама',
    18 => 'музыка',
    19 => 'мюзикл',
    20 => 'новости',
    21 => 'приключения',
    22 => 'реальное_ТВ',
    23 => 'семейный',
    24 => 'спорт',
    25 => 'ток-шоу',
    26 => 'триллер',
    27 => 'ужасы',
    28 => 'фильм-нуар',
    29 => 'фантастика',
    30 => 'фэнтези',
    ];

try {
    $db = new \mysqli($params['host'], $params['user'], $params['password'], $params['database']);
} catch (\Exception $e) {
    echo 'Подключение не удалось: ' . $e->getMessage();
}

$db->query("SET NAMES 'utf8'");

$result = $db->query("SELECT * FROM article");
//var_dump($result);

$articles = [];
echo ("<pre>");

while ($row = $result->fetch_assoc()) {
//    echo ("_________________________________________");
//    var_dump($articles);
//    echo ("_________________________________________");

    $articles[] = $row;
}

foreach ($articles as $indexA => $article) {
    $ganre_str = "";
    $ganre_num = "";
    foreach ($genres as $indexG => $genre) {
        if (strpos($article['full_text'], $genre)) {
            $ganre_str = $ganre_str.$genre.',';
            $ganre_num = $ganre_num.$indexG.',';
        }
    }

    if (!empty($ganre_str)) {
        $ganre_num = '"'.$ganre_num.'"';
        $ganre_str = '"'.$ganre_str.'"';
        $sql = "UPDATE article SET `film_genre_num` = $ganre_num , `film_genre_str` = $ganre_str  WHERE `id`=".$article['id'];
//        echo $sql,"\n\t";
        try {
        $result = $db->query($sql);
        }catch (\Exception $e) {
            echo $e->getMessage();
        }
        if ($result) {
            echo $result;
            echo "OK! ".$article['id']."--".$ganre_str." --- ".$ganre_num."<br>";
        } else {
            echo $result;
            echo "ERROR! ".$article['id']."--".$ganre_str." --- ".$ganre_num."<br>";
        }
    }
}





$db->close();













//echo ("<br><pre>");
//var_dump($articles);

//$db = new \mysqli($params['host'], $params['user'], $params['password'], $params['database']);
//$db->query("SET NAMES 'utf8'");
//
//$result = $db->query("DELETE FROM `users` WHERE `gender` = 2 AND `name` = 'Test2'");
//$result = $db->query("DELETE FROM `users` WHERE `gender` > 1 OR `gender` = 0");
//
//$db->close();
//
//debug($customers);
