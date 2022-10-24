<?php
use App\Helpers\Text;
use App\Model\Post;
use App\Connection;
use App\PaginatedQuery;
use App\URL;
use App\Model\Category;

$title = 'Mon  Blog';
$pdo = Connection::getPDO();

$paginatedQuery = new PaginatedQuery(
    "SELECT * FROM post ORDER BY created_at DESC",
    "SELECT COUNT(id) FROM post"
);

$posts = $paginatedQuery->getItems(Post::class);
$postsById = [];
foreach($posts as $post) {
    $postsByID[$post->getID()] = $post;
    $ids[] = $post->getID(); 
}
$categories = $pdo
    ->query(
        '
        SELECT c.*, pc.post_id
        FROM post_category pc
        JOIN category c ON c.id = pc.category_id
        WHERE pc.post_id IN (' . implode(',', array_keys($postsByID))  . ')
        '
    )->fetchAll(PDO::FETCH_CLASS, Category::class);

// On parcourt les catégories
foreach($categories as $category){
    $postsByID[$category->getPostID()]->addCategory($category);
}
    // On trouve l'article $posts correspondant à la ligne
    // On ajoute la catégorie à l'articles

$link = $router->url('home');

?>

<h1>Mon Blog</h1>

<div class="row">
    <?php foreach($posts as $post): ?>
    <div class="col-md-3">
        <?php require 'card.php' ?>
    </div>
    <?php endforeach ?>
</div>


<div class="d-flex justify-content-between my-4">
    <?= $paginatedQuery->previousLink($link);?>
    <?= $paginatedQuery->nextLink($link);?>
</div>