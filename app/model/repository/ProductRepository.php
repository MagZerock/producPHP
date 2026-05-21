<?php
namespace App\Model\Repository;

use MongoDB\Client;
use App\Model\Entity\Product;

require_once __DIR__ . '/../../config/config.php';

class ProductRepository {
    private $collection;

    public function __construct() {
        $client = new Client(MONGODB_URI);
        
        $typeMap = [
            'root' => Product::class,
            'document' => 'array',
            'array' => 'array'
        ];
        
        $this->collection = $client->selectCollection(DB_NAME, COLLECTION_NAME, [
            'typeMap' => $typeMap
        ]);
    }

    /**
     * Find all products in MongoDB and return them as an array of Product entities.
     * 
     * @return Product[]
     * @throws \Exception
     */
    public function findAll(): array {
        return $this->collection->find()->toArray();
    }

    /**
     * Save a Product entity to MongoDB.
     * 
     * @param Product $product
     * @return bool
     * @throws \Exception
     */
    public function save(Product $product): bool {
        $result = $this->collection->insertOne($product);
        return $result->getInsertedCount() > 0;
    }
}
