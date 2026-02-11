<?php
require_once 'db_config.php';

class CategoryManager
{
    private $pdo;

    public function __construct($database)
    {
        $this->pdo = $database;
    }

    // Get all categories
    public function getAllCategories()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE is_active = TRUE ORDER BY name");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Get category by ID
    public function getCategoryById($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Create new category
    public function createCategory($name, $description = '', $imageUrl = '')
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO categories (name, description, image_url) VALUES (?, ?, ?)");
            $result = $stmt->execute([$name, $description, $imageUrl]);

            if ($result) {
                return ['success' => true, 'message' => 'Category created successfully', 'id' => $this->pdo->lastInsertId()];
            } else {
                return ['success' => false, 'message' => 'Failed to create category'];
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return ['success' => false, 'message' => 'Category with this name already exists'];
            }
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    // Update category
    public function updateCategory($id, $name, $description = '', $imageUrl = '')
    {
        try {
            $sql = "UPDATE categories SET name = ?, description = ?";
            $params = [$name, $description];

            if ($imageUrl) {
                $sql .= ", image_url = ?";
                $params[] = $imageUrl;
            }

            $sql .= " WHERE id = ?";
            $params[] = $id;

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);

            if ($result) {
                return ['success' => true, 'message' => 'Category updated successfully'];
            } else {
                return ['success' => false, 'message' => 'Failed to update category'];
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return ['success' => false, 'message' => 'Category with this name already exists'];
            }
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    // Delete category (soft delete)
    public function deleteCategory($id)
    {
        try {
            // Check if any products are using this category name (legacy string check) or ID (future)
            // For now just soft delete
            $stmt = $this->pdo->prepare("UPDATE categories SET is_active = FALSE WHERE id = ?");
            $result = $stmt->execute([$id]);

            if ($result) {
                return ['success' => true, 'message' => 'Category deleted successfully'];
            } else {
                return ['success' => false, 'message' => 'Failed to delete category'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
}
?>