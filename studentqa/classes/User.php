<?php
class User {
    // Database connection and table name
    private $conn;
    private $table_name = "users";

    // Object properties
    public $user_id;
    public $username;
    public $email;
    public $password;
    public $created_at;

    // Constructor with database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Create user
    public function create() {
        // Sanitize input
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        // Query to insert record
        $query = "INSERT INTO " . $this->table_name . "
                  SET username=:username, email=:email, password=:password";

        // Prepare query
        $stmt = $this->conn->prepare($query);

        // Bind values
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);

        // Execute query
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Read all users
    public function read() {
        // Query to select all users
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY username";

        // Prepare query statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Read single user
    public function readOne() {
        // Query to read single record
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = ? LIMIT 0,1";

        // Prepare query statement
        $stmt = $this->conn->prepare($query);

        // Bind id of user to be read
        $stmt->bindParam(1, $this->user_id);

        // Execute query
        $stmt->execute();

        // Get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set values to object properties
        if($row) {
            $this->username = $row['username'];
            $this->email = $row['email'];
            $this->created_at = $row['created_at'];
            return true;
        }
        
        return false;
    }

    // Update user
    public function update() {
        // Sanitize input
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));

        // Query to update record
        $query = "UPDATE " . $this->table_name . "
                  SET username=:username, email=:email
                  WHERE user_id=:user_id";

        // Prepare query
        $stmt = $this->conn->prepare($query);

        // Bind values
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":user_id", $this->user_id);

        // Execute query
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Delete user
    public function delete() {
        // Query to delete record
        $query = "DELETE FROM " . $this->table_name . " WHERE user_id = ?";

        // Prepare query
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));

        // Bind user id
        $stmt->bindParam(1, $this->user_id);

        // Execute query
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Login user
    public function login() {
        // Query to find user by username
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = ? LIMIT 0,1";

        // Prepare query statement
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->username = htmlspecialchars(strip_tags($this->username));

        // Bind username
        $stmt->bindParam(1, $this->username);

        // Execute query
        $stmt->execute();

        // Check if user exists
        if($stmt->rowCount() > 0) {
            // Get user data
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verify password
            if(password_verify($this->password, $row['password'])) {
                // Set object properties from database
                $this->user_id = $row['user_id'];
                $this->email = $row['email'];
                $this->created_at = $row['created_at'];
                return true;
            }
        }
        
        return false;
    }
}
?>