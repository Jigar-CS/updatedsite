<?php

class ImageRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllImages() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM images ORDER BY created_at DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("ImageRepository getAllImages error: " . $e->getMessage());
            return [];
        }
    }

    public function addImage($filename, $path, $title = null, $description = null) {
        try {
            $stmt = $this->db->prepare("INSERT INTO images (filename, path, title, description) VALUES (:filename, :path, :title, :description)");
            $stmt->bindParam(':filename', $filename);
            $stmt->bindParam(':path', $path);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("ImageRepository addImage error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteImage($imageId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM images WHERE id = :id");
            $stmt->bindParam(':id', $imageId);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("ImageRepository deleteImage error: " . $e->getMessage());
            return false;
        }
    }

    public function getImageById($imageId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM images WHERE id = :id");
            $stmt->bindParam(':id', $imageId);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("ImageRepository getImageById error: " . $e->getMessage());
            return false;
        }
    }

    public function updateImage($id, $title, $description) {
        try {
            $stmt = $this->db->prepare("UPDATE images SET title = :title, description = :description WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("ImageRepository updateImage error: " . $e->getMessage());
            return false;
        }
    }
}