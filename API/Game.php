<?php

// 1.1
class Game
{
    // 1.2
    public $id = null;
    public $name = null;
    public $image_url = null;
    public $description = null;
    public $rating = null;
    public $released_at = null;

    // 1.9
    public $genres = [];

    // 1.10
    public function addGenre(Genre $genre)
    {
        $this->genres[] = $genre;
    }
}