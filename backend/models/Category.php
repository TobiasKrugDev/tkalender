<?php
    class Category {
        private $conn;

        public $id;
        public $name;
        public $description;
        public $color;

        public $limit;
        public $offset;
        public $searchQuery;

        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Category List
        public function read() {
            $query = 'SELECT * FROM categories LIMIT ?, ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->offset, PDO::PARAM_INT);
            $stmt->bindParam(2, $this->limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt;
        }

        // Get Single Category
        public function read_single() {

            $query = 'SELECT * FROM categories WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->color = $row['color'];
        }

        // Create Category
        public function create() {
            $query = 'INSERT INTO categories 
            SET 
                name = :name,
                description = :description,
                color = :color';

            $stmt = $this->conn->prepare($query);

            // Sanitize user input
            $this->name = isset($this->name) ? htmlspecialchars(strip_tags($this->name)) : null;
            $this->description = isset($this->description) ? htmlspecialchars(strip_tags($this->description)) : null;
            $this->color = isset($this->color) ? htmlspecialchars(strip_tags($this->color)) : null;

            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':color', $this->color);

            if ($stmt->execute()) {
                return true;
            }

            // Print error message
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        // Update Category
        public function update() {
            $query = 'UPDATE categories 
            SET 
                name = :name,
                description = :description,
                color = :color
            WHERE 
                id = :id';

            $stmt = $this->conn->prepare($query);

            // Sanitize user input
            $this->name = isset($this->name) ? htmlspecialchars(strip_tags($this->name)) : null;
            $this->description = isset($this->description) ? htmlspecialchars(strip_tags($this->description)) : null;
            $this->color = isset($this->color) ? htmlspecialchars(strip_tags($this->color)) : null;
            $this->id = isset($this->id) ? htmlspecialchars(strip_tags($this->id)) : null;

            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':color', $this->color);
            $stmt->bindParam(':id', $this->id);

            if ($stmt->execute()) {
                return true;
            }

            // Print error message
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        // Delete Category
        public function delete() {
            $query = 'DELETE FROM categories WHERE id = :id';
            $stmt = $this->conn->prepare($query);
            $this->id = isset($this->id) ? htmlspecialchars(strip_tags($this->id)) : null;
            $stmt->bindParam(':id', $this->id);

            if ($stmt->execute()) {
                return true;
            }

            // Print error message
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        // Search Categories
        public function search() {
            $query = '
                SELECT * 
                FROM categories 
                WHERE name LIKE ? OR description LIKE ?
                LIMIT ?, ?';
            $stmt = $this->conn->prepare($query);
            $searchQuery = "%".$this->searchQuery."%";
            $stmt->bindParam(1, $searchQuery, PDO::PARAM_STR);
            $stmt->bindParam(2, $searchQuery, PDO::PARAM_STR);
            $stmt->bindParam(3, $this->offset, PDO::PARAM_INT);
            $stmt->bindParam(4, $this->limit, PDO::PARAM_INT);
            $stmt->execute();

            
            return $stmt;
        }
    }