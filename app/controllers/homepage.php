<?php

use App\QueryBuilder;

$db = new QueryBuilder();
// $db->insert(['title' => 'new title'], 'posts');
$db->delete(49, 'posts');
$db->update(['title' => 'title'], 50, 'posts');
$posts = $db->getAll('posts');

var_dump($posts);
var_dump($db->getOne(3, 'posts'));
