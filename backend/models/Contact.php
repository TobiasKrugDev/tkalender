<?php
    class Contact {
        private $conn;

        public $id;
        public $firstname;
        public $lastname;
        public $description;
        public $phoneNumber;
        public $emailAddress;
        public $image;

        public $limit;
        public $offset;
        public $searchQuery;
        public $appointmentFilter;

        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Contact List
        public function read() {
            if (isset($this->appointmentFilter)) {
                $query = '
                    SELECT * 
                    FROM participations JOIN contacts ON participations.contact = contacts.id
                    WHERE participations.appointment = ?';
                    // No pagination here
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(1, $this->appointmentFilter);
                $stmt->execute();
            } else {
                $query = 'SELECT * FROM contacts LIMIT ?, ?';
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(1, $this->offset, PDO::PARAM_INT);
                $stmt->bindParam(2, $this->limit, PDO::PARAM_INT);
                $stmt->execute();
            }
            
            return $stmt;
        }

        // Get Single Contact
        public function read_single() {

            $query = 'SELECT * FROM contacts WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->description = $row['description'];
            $this->phoneNumber = $row['phone_number'];
            $this->emailAddress = $row['email_address'];
            $this->image = $row['image'];
        }

        // Create Contact
        public function create() {
            $query = 'INSERT INTO contacts 
            SET 
                firstname = :firstname,
                lastname = :lastname,
                description = :description,
                phone_number = :phoneNumber,
                email_address = :emailAddress,
                image = :image';

            $stmt = $this->conn->prepare($query);

            // Sanitize user input
            $this->firstname = isset($this->firstname) ? htmlspecialchars(strip_tags($this->firstname)) : null;
            $this->lastname = isset($this->lastname) ? htmlspecialchars(strip_tags($this->lastname)) : null;
            $this->description = isset($this->description) ? htmlspecialchars(strip_tags($this->description)) : null;
            $this->phoneNumber = isset($this->phoneNumber) ? htmlspecialchars(strip_tags($this->phoneNumber)) : null;
            $this->emailAddress = isset($this->emailAddress) ? htmlspecialchars(strip_tags($this->emailAddress)) : null;
            $this->image = isset($this->image) ? htmlspecialchars(strip_tags($this->image)) : null;

            $stmt->bindParam(':firstname', $this->firstname);
            $stmt->bindParam(':lastname', $this->lastname);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':phoneNumber', $this->phoneNumber);
            $stmt->bindParam(':emailAddress', $this->emailAddress);
            $stmt->bindParam(':image', $this->image);

            if ($stmt->execute()) {
                return true;
            }

            // Print error message
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        // Update Contact
        public function update() {
            $query = 'UPDATE contacts 
            SET 
                firstname = :firstname,
                lastname = :lastname,
                description = :description,
                phone_number = :phoneNumber,
                email_address = :emailAddress,
                image = :image
            WHERE
                id = :id';

            $stmt = $this->conn->prepare($query);

            // Sanitize user input
            $this->firstname = isset($this->firstname) ? htmlspecialchars(strip_tags($this->firstname)) : null;
            $this->lastname = isset($this->lastname) ? htmlspecialchars(strip_tags($this->lastname)) : null;
            $this->description = isset($this->description) ? htmlspecialchars(strip_tags($this->description)) : null;
            $this->phoneNumber = isset($this->phoneNumber) ? htmlspecialchars(strip_tags($this->phoneNumber)) : null;
            $this->emailAddress = isset($this->emailAddress) ? htmlspecialchars(strip_tags($this->emailAddress)) : null;
            $this->image = isset($this->image) ? htmlspecialchars(strip_tags($this->image)) : null;
            $this->id = isset($this->id) ? htmlspecialchars(strip_tags($this->id)) : null;

            $stmt->bindParam(':firstname', $this->firstname);
            $stmt->bindParam(':lastname', $this->lastname);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':phoneNumber', $this->phoneNumber);
            $stmt->bindParam(':emailAddress', $this->emailAddress);
            $stmt->bindParam(':image', $this->image);
            $stmt->bindParam(':id', $this->id);

            if ($stmt->execute()) {
                return true;
            }

            // Print error message
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        // Delete Contact
        public function delete() {
            $query = 'DELETE FROM contacts WHERE id = :id';
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

        // Search Contacts
        public function search() {
            $query = '
                SELECT * 
                FROM contacts 
                WHERE firstname LIKE ? OR lastname LIKE ? OR description LIKE ?
                LIMIT ?, ?';
            $stmt = $this->conn->prepare($query);
            $searchQuery = "%".$this->searchQuery."%";
            $stmt->bindParam(1, $searchQuery, PDO::PARAM_STR);
            $stmt->bindParam(2, $searchQuery, PDO::PARAM_STR);
            $stmt->bindParam(3, $searchQuery, PDO::PARAM_STR);
            $stmt->bindParam(4, $this->offset, PDO::PARAM_INT);
            $stmt->bindParam(5, $this->limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt;
        }
    }