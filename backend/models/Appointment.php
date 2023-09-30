<?php
    class Appointment {
        private $conn;

        public $id;
        public $name;
        public $description;
        public $startAt;
        public $endAt;
        public $location;
        public $category;
        public $contacts;
        public $icon;

        public $limit;
        public $offset;
        public $sortBy;
        public $orderDirection;
        public $timespanStart;
        public $searchQuery;
        public $contactFilter;
        public $locationFilter;
        public $calendarMode;
        public $calendarTimespanStart;
        public $calendarTimespanEnd;

        public function __construct($db) {
            $this->conn = $db;
        }

        // Get Appointments
        // ToDo: Sorting
        public function read() {
            if (isset($this->calendarMode) && $this->calendarMode == true) {
                $query = 'SELECT appointments.id, appointments.name AS appointmentName, appointments.description AS appointmentDescription, startAt, endAt, icon, categories.id AS categoryID, categories.name AS categoryName, categories.description AS categoryDescription, color, locations.id AS locationID, locations.name AS locationName, locations.description AS locationDescription, street_address AS streetAddress, postal_code AS postalCode, city
                    FROM appointments LEFT JOIN categories ON appointments.category = categories.id LEFT JOIN locations ON appointments.location = locations.id
                    WHERE appointments.startAt BETWEEN ? AND ?';
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(1, $this->calendarTimespanStart, PDO::PARAM_STR);
                    $stmt->bindParam(2, $this->calendarTimespanEnd, PDO::PARAM_STR);
                    $stmt->execute();
            } else {
                if (isset($this->contactFilter)) {
                    // ToDo
                    $query = '
                        SELECT * 
                        FROM participations JOIN appointments ON participations.appointment = appointments.id
                        WHERE participations.contact = ?
                        LIMIT ?, ?';
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(1, $this->contactFilter);
                    $stmt->bindParam(2, $this->offset, PDO::PARAM_INT);
                    $stmt->bindParam(3, $this->limit, PDO::PARAM_INT);
                    $stmt->execute();
                } elseif (isset($this->locationFilter)) {
                    // ToDo
                    $query = '
                        SELECT * 
                        FROM appointments
                        WHERE location = ?
                        LIMIT ?, ?';
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(1, $this->locationFilter);
                    $stmt->bindParam(2, $this->offset, PDO::PARAM_INT);
                    $stmt->bindParam(3, $this->limit, PDO::PARAM_INT);
                    $stmt->execute();
                } else {
                    // ToDo: Look at this beauty
                    $query = 'SELECT appointments.id, appointments.name AS appointmentName, appointments.description AS appointmentDescription, startAt, endAt, icon, categories.id AS categoryID, categories.name AS categoryName, categories.description AS categoryDescription, color, locations.id AS locationID, locations.name AS locationName, locations.description AS locationDescription, street_address AS streetAddress, postal_code AS postalCode, city
                    FROM appointments LEFT JOIN categories ON appointments.category = categories.id LEFT JOIN locations ON appointments.location = locations.id
                    WHERE appointments.startAt > ? AND (appointments.name LIKE ? OR appointments.description LIKE ?)  -- ToDo: Add more properties
                    ORDER BY startAt ASC
                    LIMIT ?, ?';
                    $stmt = $this->conn->prepare($query);
                    $searchQuery = "%".$this->searchQuery."%";
                    $stmt->bindParam(1, $this->timespanStart, PDO::PARAM_STR);
                    $stmt->bindParam(2, $searchQuery, PDO::PARAM_STR);
                    $stmt->bindParam(3, $searchQuery, PDO::PARAM_STR);
                    $stmt->bindParam(4, $this->offset, PDO::PARAM_INT);
                    $stmt->bindParam(5, $this->limit, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }

    
            
            return $stmt;
        }

        // Get Total Items Number
        public function count() {
            if (isset($this->calendarMode) && $this->calendarMode == true) {
                
                $query = 'SELECT COUNT(*) AS total 
                FROM appointments
                WHERE startAt > ? AND startAt < ?';
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(1, $this->calendarTimespanStart, PDO::PARAM_STR);
                $stmt->bindParam(2, $this->calendarTimespanEnd, PDO::PARAM_STR);
                $stmt->execute();

            } else {

                $query = 'SELECT COUNT(*) AS total 
                FROM appointments
                WHERE appointments.startAt > ? AND (appointments.name LIKE ? OR appointments.description LIKE ?)'; // ToDo: Add more properties
                $stmt = $this->conn->prepare($query);
                $searchQuery = "%".$this->searchQuery."%";
                $stmt->bindParam(1, $this->timespanStart, PDO::PARAM_STR);
                $stmt->bindParam(2, $searchQuery, PDO::PARAM_STR);
                $stmt->bindParam(3, $searchQuery, PDO::PARAM_STR);
                $stmt->execute();
            }

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'];
        }

        // Get Single Appointment
        public function read_single() {
            $query = 'SELECT appointments.id, appointments.name AS appointmentName, appointments.description AS appointmentDescription, startAt, endAt, icon, categories.id AS categoryID, categories.name AS categoryName, categories.description AS categoryDescription, color, locations.id AS locationID, locations.name AS locationName, locations.description AS locationDescription, street_address AS streetAddress, postal_code AS postalCode, city
            FROM appointments LEFT JOIN categories ON appointments.category = categories.id LEFT JOIN locations ON appointments.location = locations.id
            WHERE appointments.id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->name = $row['appointmentName'];
            $this->description = $row['appointmentDescription'];
            $this->startAt = $row['startAt'];
            $this->endAt = $row['endAt'];
            $this->icon = $row['icon'];

            if (isset($row['locationID'])) {
                $this->location = array(
                    'id' => $row['locationID'],
                    'name' => $row['locationName'],
                    'description' => $row['locationDescription'],
                    'streetAddress' => $row['streetAddress'],
                    'postalCode' => $row['postalCode'],
                    'city' => $row['city']
                );
            } else {
                $this->location = null;
            }

            if (isset($row['categoryID'])) {
                $this->category = array(
                    'id' => $row['categoryID'],
                    'name' => $row['categoryName'],
                    'description' => $row['categoryDescription'],
                    'color' => $row['color'],
                );
            } else {
                $this->category = null;
            }

            return $stmt;
        }

        // Create Appointment
        public function create() {
            $query = 'INSERT INTO appointments 
            SET 
                name = :name,
                description = :description,
                startAt = :startAt,
                endAt = :endAt,
                location = :location,
                category = :category,
                icon = :icon';

            $stmt = $this->conn->prepare($query);

            // Sanitize user input
            $this->name = isset($this->name) ? htmlspecialchars(strip_tags($this->name)) : null;
            $this->description = isset($this->description) ? htmlspecialchars(strip_tags($this->description)) : null;
            $this->startAt = isset($this->startAt) ? htmlspecialchars(strip_tags($this->startAt)) : null;
            $this->endAt = isset($this->endAt) ? htmlspecialchars(strip_tags($this->endAt)) : null;
            $this->location = isset($this->location) ? htmlspecialchars(strip_tags($this->location)) : null;
            $this->category = isset($this->category) ? htmlspecialchars(strip_tags($this->category)) : null;
            $this->icon = isset($this->icon) ? htmlspecialchars(strip_tags($this->icon)) : null;

            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':startAt', $this->startAt);
            $stmt->bindParam(':endAt', $this->endAt);
            $stmt->bindParam(':location', $this->location);
            $stmt->bindParam(':category', $this->category);
            $stmt->bindParam(':icon', $this->icon);

            $stmt->execute();

            $createdAppointmentID = $this->conn->lastInsertId();

            // Add appointment contact connections
            foreach ($this->contacts as $contact) {
                $query = 'INSERT INTO participations 
                SET 
                    appointment = :appointmentID,
                    contact = :contactID';

                $stmt = $this->conn->prepare($query);

                // Sanitize user input
                $contactID = htmlspecialchars(strip_tags($contact));

                $stmt->bindParam(':appointmentID', $createdAppointmentID);
                $stmt->bindParam(':contactID', $contactID);

                $stmt->execute();
            }

            // Return ID of created item
            return $createdAppointmentID;
        }

        // Update Appointment
        public function update() {
            $query = 'UPDATE appointments 
            SET 
                name = :name,
                description = :description,
                startAt = :startAt,
                endAt = :endAt,
                location = :location,
                category = :category,
                icon = :icon
            WHERE
                id = :id';

            $stmt = $this->conn->prepare($query);

            // Sanitize user input
            $this->name = isset($this->name) ? htmlspecialchars(strip_tags($this->name)) : null;
            $this->description = isset($this->description) ? htmlspecialchars(strip_tags($this->description)) : null;
            $this->startAt = isset($this->startAt) ? htmlspecialchars(strip_tags($this->startAt)) : null;
            $this->endAt = isset($this->endAt) ? htmlspecialchars(strip_tags($this->endAt)) : null;
            $this->location = isset($this->location) ? htmlspecialchars(strip_tags($this->location)) : null;
            $this->category = isset($this->category) ? htmlspecialchars(strip_tags($this->category)) : null;
            $this->icon = isset($this->icon) ? htmlspecialchars(strip_tags($this->icon)) : null;
            $this->id = isset($this->id) ? htmlspecialchars(strip_tags($this->id)) : null;

            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':startAt', $this->startAt);
            $stmt->bindParam(':endAt', $this->endAt);
            $stmt->bindParam(':location', $this->location);
            $stmt->bindParam(':category', $this->category);
            $stmt->bindParam(':icon', $this->icon);
            $stmt->bindParam(':id', $this->id);

            $stmt->execute();

            // Update appointment contact connections
            $query = 'DELETE FROM participations WHERE appointment = :id';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->id);

            $stmt->execute();

            foreach ($this->contacts as $contact) {
                $query = 'INSERT INTO participations 
                SET 
                    appointment = :appointmentID,
                    contact = :contactID';

                $stmt = $this->conn->prepare($query);

                // Sanitize user input
                $contactID = htmlspecialchars(strip_tags($contact));

                $stmt->bindParam(':appointmentID', $this->id);
                $stmt->bindParam(':contactID', $contactID);

                $stmt->execute();
            }
        }

        // Delete Appointment
        public function delete() {
            $query = 'DELETE FROM appointments WHERE id = :id';
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
    }