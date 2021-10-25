<?php
session_start();
// Load config
$config = parse_ini_file('conf.ini');
$config['webroot'] = __DIR__;

// Setup database connection
$dbc = new PDO("mysql:host=".$config['db']['host'].";port=".$config['db']['port'].";dbname=" .$config['db']['dbname'],
        $config['db']['user'], $config['db']['password']);

// Setup dependency container
$dc = array(
    'dbc' => $dbc,
    'config' => $config
);

// Functions
include('functions.php');

// Initialize
$html['title'] = "Movie top 10";
$html['nav'] = "";
$html['main'] = "";
$html['footer'] = "Copyright &copy; Movie top 10 - 2021";

// Navigation
$links = array(
    array('href' => '/index.php?p=show', 'title' => 'Home'),
    array('href' => '/index.php?p=register', 'title' => 'Become a member'),
    array('href' => '/index.php?p=login', 'title' => 'Login'),
    array('href' => '/index.php?p=logout', 'title' => 'Logout'),
);


// Main
$pages = array (
    "show",
    "register",
    "detail",
    "login",
    "logout"
);

if(isset($_GET['p']) && in_array($_GET['p'], $pages)) {
    $html['main'] = $_GET['p']($dc);
} else {
    include('show.php');
}




$html['nav'] = getNavigationBar($links);

include('layout.php');