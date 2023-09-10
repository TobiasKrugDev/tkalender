<?php
    class Location {
        private $conn;

        public $id;
        public $name;
        public $description;
        public $streetAddress;
        public $postalCode;
        public $city;

        public $limit;
        public $offset;
        public $searchQuery;

        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Location List
        public function read() {
            $query = 'SELECT * FROM locations LIMIT ?, ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->offset, PDO::PARAM_INT);
            $stmt->bindParam(2, $this->limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt;
        }

        // Get Single Location
        public function read_single() {

            $query = 'SELECT * FROM locations WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->streetAddress = $row['street_address'];
            $this->postalCode = $row['postal_code'];
            $this->city = $row['city'];

            return $stmt;
        }

        // Create Location
        public function create() {
            $query = 'INSERT INTO locations 
            SET 
                name = :name,
                description = :description,
                street_address = :streetAddress,
                postal_code = :postalCode,
                city = :city';

            $stmt = $this->conn->prepare($query);

            // Sanitize user input
            $this->name = isset($this->name) ? htmlspecialchars(strip_tags($this->name)) : null;
            $this->description = isset($this->description) ? htmlspecialchars(strip_tags($this->description)) : null;
            $this->streetAddress = isset($this->streetAddress) ? htmlspecialchars(strip_tags($this->streetAddress)) : null;
            $this->postalCode = isset($this->postalCode) ? htmlspecialchars(strip_tags($this->postalCode)) : null;
            $this->city = isset($this->city) ? htmlspecialchars(strip_tags($this->city)) : null;

            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':streetAddress', $this->streetAddress);
            $stmt->bindParam(':postalCode', $this->postalCode);
            $stmt->bindParam(':city', $this->city);

            $stmt->execute();
            // Return ID of created item
            $createdLocationID = $this->conn->lastInsertId();
            return $createdLocationID;
        }

        // Update Location
        public function update() {
            $query = 'UPDATE locations 
            SET 
                name = :name,
                description = :description,
                street_address = :streetAddress,
                postal_code = :postalCode,
                city = :city
            WHERE
                id = :id';

            $stmt = $this->conn->prepare($query);

            // Sanitize user input
            $this->name = isset($this->name) ? htmlspecialchars(strip_tags($this->name)) : null;
            $this->description = isset($this->description) ? htmlspecialchars(strip_tags($this->description)) : null;
            $this->streetAddress = isset($this->streetAddress) ? htmlspecialchars(strip_tags($this->streetAddress)) : null;
            $this->postalCode = isset($this->postalCode) ? htmlspecialchars(strip_tags($this->postalCode)) : null;
            $this->city = isset($this->city) ? htmlspecialchars(strip_tags($this->city)) : null;
            $this->id = isset($this->id) ? htmlspecialchars(strip_tags($this->id)) : null;

            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':streetAddress', $this->streetAddress);
            $stmt->bindParam(':postalCode', $this->postalCode);
            $stmt->bindParam(':city', $this->city);
            $stmt->bindParam(':id', $this->id);

            if ($stmt->execute()) {
                return true;
            }

            // Print error message
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        // Delete Location
        public function delete() {
            $query = 'DELETE FROM locations WHERE id = :id';
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

        // Search Locations
        public function search() {
            $query = '
                SELECT * 
                FROM locations 
                WHERE name LIKE ? OR description LIKE ? OR street_address LIKE ? OR postal_code LIKE ? OR city LIKE ?
                LIMIT ?, ?';
            $stmt = $this->conn->prepare($query);
            $searchQuery = "%".$this->searchQuery."%";
            $stmt->bindParam(1, $searchQuery, PDO::PARAM_STR);
            $stmt->bindParam(2, $searchQuery, PDO::PARAM_STR);
            $stmt->bindParam(3, $searchQuery, PDO::PARAM_STR);
            $stmt->bindParam(4, $searchQuery, PDO::PARAM_STR);
            $stmt->bindParam(5, $searchQuery, PDO::PARAM_STR);
            $stmt->bindParam(6, $this->offset, PDO::PARAM_INT);
            $stmt->bindParam(7, $this->limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt;
        }
    }