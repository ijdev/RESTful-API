<?php

class Post {

    private $conn;
    private $table = 'posts';

    public $id;
    public $category_id;
    public $category_name;
    public $title;
    public $body;
    public $author;
    public $created_at;

    public function __construct($db){
        $this->conn = $db;
    }

    public function read() {
        $q = '
        SELECT 
            c.name as category_name,
            p.id, 
            p.category_id,
            p.title,
            p.author,
            p.created_at,
            p.body
        
        FROM 
        '.$this->table .' p 
        
        Left JOIN
            categories c ON p.category_id = c.id
        ORDER BY
            p.created_at DESC
        ';

        $stmt = $this->conn->prepare($q);
        $stmt->execute();
        return $stmt;

    }


    public function single_read() {
        $q = '
        SELECT 
            c.name as category_name,
            p.id, 
            p.category_id,
            p.title,
            p.author,
            p.created_at,
            p.body
        
        FROM 
        '.$this->table .' p 
        
        Left JOIN
            categories c ON p.category_id = c.id
        WHERE 
            p.id = ?
        LIMIT 0,1
        ';


        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(1,$this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->title = $row['title'];
        $this->body = $row['body'];
        $this->author = $row['author'];
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];

    }


    function create(){
        $q = ' INSERT INTO 
                    '.$this->table.' 
               SET
                    title = :title,
                    body = :body,
                    author = :author,
                    category_id = :category_id';


        $stmt = $this->conn->prepare($q);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        $stmt->bindParam(':title',$this->title);
        $stmt->bindParam(':body',$this->body);
        $stmt->bindParam(':author',$this->author);
        $stmt->bindParam(':category_id',$this->category_id);

        if ($stmt->execute()) {
            return true;
        }

        printf('Error: %s. \n',$stmt->error);
        return false;

    }
//
//
    function update(){
        $q = ' UPDATE '.$this->table.'
               SET
                    title = :title,
                    body = :body,
                    author = :author,
                    category_id = :category_id

               WHERE id = :id';


        $stmt = $this->conn->prepare($q);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':title',$this->title);
        $stmt->bindParam(':body',$this->body);
        $stmt->bindParam(':author',$this->author);
        $stmt->bindParam(':category_id',$this->category_id);
        $stmt->bindParam(':id',$this->id);


        if ($stmt->execute()) {
            return true;
        }

        printf('Error: %s. \n',$stmt->error);
        return false;

    }

    public function delete() {
        // Create query
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        // Bind data
        $stmt->bindParam(':id', $this->id);
        // Execute query
        if($stmt->execute()) {
            return true;
        }
        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);
        return false;
    }


}