<?php
use App\Connection;
use App\Model\Post;
use App\Model\Category;

$id = (int)$params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();
$query = $pdo->prepare('SELECT * FROM post WHERE id = :id');
$query->execute(['id' => $id]);
$query->setFetchMode(PDO::FETCH_CLASS, Post::class);
$post = $query->fetch();

if ($post === false) {
    throw new Exception('Aucun article ne correspond à cet id');
}

if ($post->getSlug() !== $slug) {
    $url = $router->url('post', ['slug' => $post->getSlug(), 'id' => $id]);
    http_response_code(301);
    header('Location ' . $url);
}

$query = $pdo->prepare('SELECT c.id, c.slug, c.name FROM post_category pc JOIN category c ON pc.category_id = c.id WHERE pc.post_id = :id');
$query->execute((['id' => $post->getId()]));
$query->setFetchMode(PDO::FETCH_CLASS, Category::class);
$categories = $query->fetchAll();

?>

<h1><?= htmlentities($post->getName()) ?></h1>
<p class="text-muted"><?= $post->getCreatedAt()->format('d F Y') ?></p>
<?php foreach($categories as $k => $category): ?>
    <?php if($k > 0): ?>
    , 
    <?php endif;
        $category_url = $router->url('category', ['id' => $category->getID(), 'slug' => $category->getSlug()]);
     ?>
    <a href="<?= $category_url ?>"><?= htmlentities($category->getName()) ?></a>
<?php endforeach ?>
<p><?= $post->getFormattedContent() ?></p>
