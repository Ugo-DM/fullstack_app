<?php

//! File where we connect to database and create fake datas with Faker.

use App\Connection;

require dirname(__DIR__) . '/vendor/autoload.php';

// Instantiate Faker
$faker = Faker\Factory::create('fr_FR');

//Connection to database
$pdo = Connection::getPDO();

$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('TRUNCATE TABLE post_category');
$pdo->exec('TRUNCATE TABLE post');
$pdo->exec('TRUNCATE TABLE category');
$pdo->exec('TRUNCATE TABLE user');

// Array of Posts and Categories
$posts = [];
$categories = [];

// Filling tables with fake datas with faker & inserting these datas to corresponding array. POST
for ($i = 0; $i < 50; $i++) {
    $pdo->exec("INSERT INTO post SET name='{$faker->sentence() }', slug='{$faker->slug}', created_at='{$faker->date} {$faker->time}', content='{$faker->paragraphs(rand(3,15), true)}'");
    $posts[] = $pdo->lastInsertId();
}
// Filling tables with fake datas with faker & inserting these datas to corresponding array. CATEGORIES
for ($i = 0; $i < 5; $i++) {
    $pdo->exec("INSERT INTO category SET name='{$faker->sentence(3) }', slug='{$faker->slug}'");
    $categories[] = $pdo->lastInsertId();
}

// Assigning random categories to posts into post_category table.
foreach($posts as $post) {
    $randomCategories = $faker->randomElements($categories, rand(0, count($categories)));
    foreach ($randomCategories as $category) {
        $pdo->exec("INSERT INTO post_category SET post_id=$post, category_id=$category");
    }
}

// Hashing passwords and inserting it into user table.
$password = password_hash('admin', PASSWORD_BCRYPT);
$pdo->exec("INSERT INTO user SET username='admin', password='$password'");
