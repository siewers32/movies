<?php
$query = $dbc->prepare("select m.movie_id, title, year, picture, avg(rating) as avg_rating from movie m join rating r on m.movie_id = r.movie_id group by m.movie_id, title, year, picture");
$query->execute();
$movies = $query->fetchAll(PDO::FETCH_ASSOC);

foreach($movies as $movie) {
    $html['main'] .= "<h3>".$movie['title']."</h3>"
        ."<p>".$movie['year']."</p>"
        ."<p>".makeStars(round($movie['avg_rating'], 0))."</p>"
        ."<img src='".$config['images']."/".$movie['picture']."'>"
        ."<p>".linkToMovie($movie['movie_id'])."</p>";

}