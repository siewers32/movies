
<?php

function getNavigationBar($links) {
    $nav = "";
    $loglinks = array();
    if(is_array($links)) {
        foreach($links as $link) {
            if($link['title'] != "Login" AND $link['title'] != "Logout") {
                $nav .= "<a href='".$link['href']."'>".$link['title']."<a>";
            } else {
                $loglinks[] = $link;
            }
        }
    }
    foreach($loglinks as $loglink) {
        if($loglink['title'] == "Logout" AND isset($_SESSION['login'])) {
            //gebruiker is ingelogd laat logoutbutton en email zien.
            $nav .= "<a href='".$loglink['href']."'>".$loglink['title']."<a>";
            $nav .= $_SESSION['login'];
        } elseif(!isset($_SESSION['login']) AND $loglink['title'] == "Login") {
            //gebruiker niet ingelogd laat login knop zien
            $nav .= "<a href='".$loglink['href']."'>".$loglink['title']."<a>";
        }
    }
    return $nav;
}

function makeStars($number_of_stars) {
    $stars = "<div class='stars'>";
    if($number_of_stars >= 1 && $number_of_stars <=5) {
        for($i = 1; $i <= $number_of_stars; $i++) {
            $stars .= "<div class='star closed'></div>";
        }
        for($i = 1; $i <= 5-$number_of_stars; $i++) {
            $stars .= "<div class='star'></div>";
        }
    } else {
        $stars .= "No rating yet!<span class='material-icons'>face</span>";
    }
    $stars .= "</div>";
    return $stars;
}

function linkToMovie($id) {
    return "<a href='index.php?p=detail&movie_id=".$id."'>More...</a>";
}

function show($dc) {
    $html = "";
    $query = $dc['dbc']->prepare("select m.movie_id, title, year, picture, avg(rating) as avg_rating from movie m"
        ." left join rating r on m.movie_id = r.movie_id group by m.movie_id, title, year, picture");
    $query->execute();
    $movies = $query->fetchAll(PDO::FETCH_ASSOC);

    foreach($movies as $movie) {
        $html .= "<h3>".$movie['title']."</h3>"
            ."<p>".$movie['year']."</p>"
            ."<p>".makeStars(round($movie['avg_rating'], 0))."</p>"
            ."<img src='".$dc['config']['images']."/".$movie['picture']."'>"
            ."<p>".linkToMovie($movie['movie_id'])."</p>";

    }
    return $html;
}

function detail($dc) {
    $html = "";
    $query = $dc['dbc']->prepare("select m.movie_id, title, year, picture, avg(rating) as avg_rating from movie m"
    ." left join rating r on m.movie_id = r.movie_id where m.movie_id = :movie_id"
    ." group by m.movie_id, title, year, picture");
    $query->bindParam(':movie_id', $_GET['movie_id'], PDO::PARAM_INT);
    $query->execute();
    $movie = $query->fetch(PDO::FETCH_ASSOC);

    $query = $dc['dbc']->prepare("select rating from movie m"
        ." join rating r on m.movie_id = r.movie_id"
        ." join user u on r.user_id = u.user_id"
        ." where m.movie_id = :movie_id");

    $query->bindParam(':movie_id', $_GET['movie_id'], PDO::PARAM_INT);
    $query->execute();
    $your_rating = $query->fetch(PDO::FETCH_ASSOC);


    $html.= "<p>".$movie['title']."</p>"
            ."<p>".$movie['year']."</p>"
            ."<p>Average rating: ".makeStars(round($movie['avg_rating'], 0))."</p>";

    if(!$your_rating) {
        $html .= "<p>You haven't rated this movie yet!</p>";
        $form = include('form_stars.php');
        $html .= $form("index.php", "post");
    } else {
        $html.= "This is your rating: ".makeStars($your_rating['rating']);
    }

    $html .="<img src='".$dc['config']['images']."/".$movie['picture']."'>";
    return $html;

}

function register($dc) {
    if(isset($_POST['email']) && isset($_POST['password'])) {
        //form is sent
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $query = $dc['dbc']->prepare("insert into user (email, password) values (:email, :password)");
        $query->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
        $query->bindParam(':password', $password);
        $query->execute();
        header('Location:  index.php?p=login');
    } else {
        $form = include('form_login.php');
        return $form('index.php?p=register', 'post');
    }
}

function login($dc) {
    //unset($_SESSION);
    if(isset($_POST['email']) && isset($_POST['password'])) {
        $query = $dc['dbc']->prepare("select email, password from user where email = :email");
        $query->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if($user && password_verify($_POST['password'], $user['password'])) {
            $_SESSION['login'] = $user['email'];
            header('Location:  index.php?p=show');
        } else {
            header('Location:  index.php?p=login');
        }
    } else {
        $form = include('form_login.php');
        return $form('index.php?p=login', 'post');
    }
}

function logout() {
    unset($_SESSION);
    session_destroy();
    header('Location:  index.php?p=show');
}

function ratingForm() {
    $form = include('form_stars.php');
}