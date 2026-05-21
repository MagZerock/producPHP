<?php
namespace App\Controller;

use App\Model\Entity\Product;
use App\Model\Repository\ProductRepository;

class ProductController {
    private $repository;
    private $categories = ["Beverages", "Snacks", "Dairy", "Candies", "Bakery"];

    public function __construct() {
        try {
            $this->repository = new ProductRepository();
        } catch (\Exception $e) {
            $this->repository = null;
        }
    }

    public function list() {
        header('Content-Type: application/json');
        
        if ($this->repository === null) {
            echo json_encode([
                'success' => false,
                'error' => 'Database connection could not be established.'
            ]);
            return;
        }

        try {
            $products = $this->repository->findAll();
            
            $formattedProducts = [];
            $totalPrice = 0.0;
            
            foreach ($products as $prod) {
                $idVal = $prod->getId();
                $priceVal = $prod->getPrice();
                $totalPrice += $priceVal;
                
                $formattedProducts[] = [
                    'id' => $idVal,
                    'name' => $prod->getName(),
                    'brand' => $prod->getBrand(),
                    'flavor' => $prod->getFlavor(),
                    'category' => $prod->getCategory(),
                    'price' => $priceVal
                ];
            }
            
            echo json_encode([
                'success' => true,
                'products' => $formattedProducts,
                'totalPrice' => $totalPrice
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => 'Error retrieving products: ' . $e->getMessage()
            ]);
        }
    }

    public function create(array $postData) {
        header('Content-Type: application/json');
        
        $name = trim($postData['name'] ?? '');
        $brand = trim($postData['brand'] ?? '');
        $flavor = trim($postData['flavor'] ?? '');
        $category = trim($postData['category'] ?? '');
        $price_raw = trim($postData['price'] ?? '');

        $errors = [];

        if (empty($name)) {
            $errors['name'] = 'Name is required';
        }
        if (empty($brand)) {
            $errors['brand'] = 'Brand is required';
        }
        if (empty($category) || !in_array($category, $this->categories)) {
            $errors['category'] = 'Please select a valid category';
        }
        if ($price_raw === '') {
            $errors['price'] = 'Price is required';
        } else {
            $price = floatval($price_raw);
            if ($price < 0) {
                $errors['price'] = 'Price cannot be negative';
            }
        }

        if (!empty($errors)) {
            echo json_encode([
                'success' => false,
                'errors' => $errors
            ]);
            return;
        }

        if ($this->repository === null) {
            echo json_encode([
                'success' => false,
                'errors' => ['db' => 'Database connection could not be established.']
            ]);
            return;
        }

        try {
            $product = new Product([
                'name' => $name,
                'brand' => $brand,
                'flavor' => $flavor,
                'category' => $category,
                'price' => floatval($price_raw)
            ]);
            
            $this->repository->save($product);
            
            echo json_encode([
                'success' => true
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'errors' => ['db' => 'Error saving product: ' . $e->getMessage()]
            ]);
        }
    }
}
