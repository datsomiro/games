<?php

// require necessary libraries
require_once 'DB.php';
require_once 'DB_functions.php';
require_once 'Game.php';
require_once 'Genre.php';

// connect to the database
connect('127.0.0.1', 'games', 'root', '');

if (isset($_GET['orderby'])) {
    $orderby = $_GET['orderby'];
} else {
    $orderby = 'name';
}

$orderby = $_GET['orderby'] ?? 'name'; // exactly the same as above
$orderway = $_GET['way'] ?? 'asc';

// validation of user input in URL parameters
if (!in_array($orderby, [
    'name',
    'rating',
    'id',
    'released_at'
])) {
    // if ($orderby != 'name' && $orderby != 'rating') {
    exit('No, this is not right!');
}

if (!in_array($orderway, ['asc', 'desc'])) {
    exit('No, this is not right!');
}

// select games data
$query = "
    SELECT *
    FROM `games`
    ORDER BY `{$orderby}` {$orderway}
";

// 1.3
$games = select($query, [], 'Game');

// 1.4
$games_by_ids = [];

// 1.5
foreach ($games as $game) {
    // 1.6
    $games_by_ids[$game->id] = $game;
}

// 1.11 - extra step, did not have to be here
$genres = [];

// 1.12
$query = "
    SELECT *
    FROM `genres`
";

$genres = select($query, [], 'Genre');

// 1.13
$genres_by_ids = [];

// 1.14
foreach ($genres as $genre) {
    // 1.15
    $genres_by_ids[$genre->id] = $genre;
}

// 1.16
$game_ids = [];

// 1.17
foreach ($games as $game) {
    // 1.18
    $game_ids[] = $game->id;
}

// 1.19
// $game_ids = [1, 2, 3, 4, 5, 6];
$question_marks = join(',', array_fill(0, count($game_ids), '?'));
// $question_marks = '?,?,?,?,?,?';
$query = "
    SELECT *
    FROM `game_genre`
    WHERE `game_id` IN ({$question_marks})
";

// $query = "
//     SELECT *
//     FROM `game_genre`
//     WHERE `game_id` IN (?,?,?,?,?,?)
// ";

$game_genres = select(
    $query,   //  ?, ?, ?, ?, ?, ?
    $game_ids // [1, 2, 3, 4, 5, 6]
);

// 1.20
foreach ($game_genres as $game_genre) {
    // 1.21
    // get an element from $games_by_ids using $game_genre->game_id
    
    // $games_by_ids = [
    //     1 => Game,
    //     2 => Game,
    //     3 => Game,
    //     ...
    // ];

    // $game_genre->game_id = 2

    $game  = $games_by_ids[ $game_genre->game_id ];
    $genre = $genres_by_ids[ $game_genre->genre_id ];

    $game->addGenre($genre);
}

// output games data as JSON
header('Content-type: application/json');

echo json_encode($games);