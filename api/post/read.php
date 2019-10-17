<?php

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Post.php';

    $database = new Database();
    $db = $database->connect();


    $post = new Post($db);

    $result = $post->read();

    $num = $result->rowCount();

if ($num > 0) {
    // Post array
    $posts_arr = [];
    $posts_arr['data'] = [];
     while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
         $post_item = [
             'id' => $row['id'],
             'title' => $row['title'],
             'body' => html_entity_decode($row['body']),
             'author' => $row['author'],
             'category_id' => $row['category_id'],
             'category_name' => $row['category_name']
         ];

        array_push($posts_arr, $post_item);
        array_push($posts_arr['data'], $post_item);
    }
    echo json_encode($posts_arr);
} else {

    echo json_encode([
         'message' => 'No Posts Found'
        ]);
}