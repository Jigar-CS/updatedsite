<?php
class Database {
    private $pdo;
    private $host = 'localhost';
    private $dbname = 'framed_soul';
    private $username = 'root';
    private $password = '';
    private $port = '3306';

    public function __construct() {
        try {
            // First, try to create the database if it doesn't exist
            $this->createDatabaseIfNotExists();
            
            // Direct connection to database
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset=utf8";
            $this->pdo = new PDO($dsn, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Initialize tables and data
            $this->initializeTables();
            
        } catch (PDOException $e) {
            error_log('Database connection failed: ' . $e->getMessage());
            throw new Exception('Database connection failed. MySQL Error: ' . $e->getMessage());
        }
    }

    private function createDatabaseIfNotExists() {
        try {
            // Connect without database name first
            $dsn = "mysql:host={$this->host};port={$this->port};charset=utf8";
            $pdo = new PDO($dsn, $this->username, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Create database if it doesn't exist
            $pdo->exec("CREATE DATABASE IF NOT EXISTS {$this->dbname}");
        } catch (PDOException $e) {
            throw new Exception("Cannot create database: " . $e->getMessage());
        }
    }

    private function initializeTables() {
        // Create images table
        $createImagesTable = "
        CREATE TABLE IF NOT EXISTS images (
            id INT AUTO_INCREMENT PRIMARY KEY,
            filename VARCHAR(255) NOT NULL,
            path VARCHAR(255) NOT NULL,
            title VARCHAR(255),
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        $this->pdo->exec($createImagesTable);

        // Create admin_users table
        $createAdminTable = "
        CREATE TABLE IF NOT EXISTS admin_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $this->pdo->exec($createAdminTable);

        // Insert sample data if tables are empty
        $this->insertSampleData();
    }

    private function insertSampleData() {
        // Check if images table is empty
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM images");
        $result = $stmt->fetch();
        
        if ($result['count'] == 0) {
            // Insert all 36 images
            $images = [];
            
            // Images 1-33 are webp
            for ($i = 1; $i <= 33; $i++) {
                $filename = "img{$i}.webp";
                // Check if file exists in uploads folder
                $uploadPath = __DIR__ . '/../public/uploads/' . $filename;
                if (file_exists($uploadPath)) {
                    $images[] = [
                        'filename' => $filename,
                        'path' => $filename,
                        'title' => "Image $i",
                        'description' => "Beautiful photograph capturing a unique moment in time."
                    ];
                }
            }
            
            // Images 34-36 are jpg
            for ($i = 34; $i <= 36; $i++) {
                $filename = "img{$i}.jpg";
                $uploadPath = __DIR__ . '/../public/uploads/' . $filename;
                if (file_exists($uploadPath)) {
                    $images[] = [
                        'filename' => $filename,
                        'path' => $filename,
                        'title' => "Image $i",
                        'description' => "Beautiful photograph capturing a unique moment in time."
                    ];
                }
            }

            // Insert all found images
            if (!empty($images)) {
                $insertStmt = $this->pdo->prepare("INSERT INTO images (filename, path, title, description) VALUES (?, ?, ?, ?)");
                foreach ($images as $image) {
                    $insertStmt->execute([
                        $image['filename'], 
                        $image['path'], 
                        $image['title'], 
                        $image['description']
                    ]);
                }
            }
        }

        // Check if admin user exists
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM admin_users WHERE username = 'admin'");
        $result = $stmt->fetch();
        
        if ($result['count'] == 0) {
            // Create default admin user
            $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("INSERT INTO admin_users (username, password, email) VALUES (?, ?, ?)");
            $stmt->execute(['admin', $adminPassword, 'admin@framedsoul.com']);
        }
    }

    public function getPdo() {
        return $this->pdo;
    }

    public function prepare($query) {
        return $this->pdo->prepare($query);
    }

    public function query($query) {
        return $this->pdo->query($query);
    }

    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }

    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }

    public function commit() {
        return $this->pdo->commit();
    }

    public function rollback() {
        return $this->pdo->rollback();
    }
}
?>