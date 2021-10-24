<?php
// Load config
$config = parse_ini_file('conf.ini');
$config['webroot'] = __DIR__;

// Initialize
$html['title'] = "Movie top 10";
$html['nav'] = "";
$html['main'] = "";
$html['footer'] = "Copyright &copy; Movie top 10 - 2021";

// Navigation
$links = array(
    array('href' => '/index.php', 'title' => 'Home'),
    array('href' => '/register.php', 'title' => 'Become a member')
);

foreach($links as $link) {
    $html['nav'] .= "<a href='".$link['href']."'>".$link['title']."<a>";
}

// Main
$dbc = new PDO("mysql:host=".$config['db']['host'].";port=".$config['db']['port'].";dbname=" .$config['db']['dbname'],
    $config['db']['user'], $config['db']['password']);
$query = $dbc->prepare("select * from movie");
$query->execute();
$movies = $query->fetchAll(PDO::FETCH_ASSOC);

foreach($movies as $movie) {
    $html['main'] .= "<h3>".$movie['title']."</h3>"
        ."<p>".$movie['year']."</p>"
        ."<img src='".$config['images']."/".$movie['picture']."'>";

}

var_dump($config);

include('layout.php');