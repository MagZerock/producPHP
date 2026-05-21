<?php
namespace App\Model\Entity;

use MongoDB\BSON\Serializable;
use MongoDB\BSON\Unserializable;
use MongoDB\BSON\ObjectId;

class Product implements Serializable, Unserializable {
    private $id;
    private $name;
    private $brand;
    private $flavor;
    private $category;
    private $price;

    public function __construct(array $attributes = []) {
        $this->name = $attributes['name'] ?? null;
        $this->brand = $attributes['brand'] ?? null;
        $this->flavor = $attributes['flavor'] ?? null;
        $this->category = $attributes['category'] ?? null;
        $this->price = isset($attributes['price']) ? floatval($attributes['price']) : 0.0;
        if (isset($attributes['id'])) {
            $this->id = $attributes['id'];
        }
    }

    public function bsonUnserialize(array $data): void {
        $this->id = isset($data['_id']) ? (string)$data['_id'] : null;
        $this->name = $data['name'] ?? null;
        $this->brand = $data['brand'] ?? null;
        $this->flavor = $data['flavor'] ?? null;
        $this->category = $data['category'] ?? null;
        $this->price = isset($data['price']) ? floatval($data['price']) : 0.0;
    }

    public function bsonSerialize(): array {
        $data = [
            'name' => $this->name,
            'brand' => $this->brand,
            'flavor' => $this->flavor,
            'category' => $this->category,
            'price' => $this->price
        ];
        
        if ($this->id !== null) {
            try {
                $data['_id'] = new ObjectId($this->id);
            } catch (\Exception $e) {
            }
        }
        
        return $data;
    }

    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getBrand() { return $this->brand; }
    public function getFlavor() { return $this->flavor; }
    public function getCategory() { return $this->category; }
    public function getPrice() { return $this->price; }

    public function setId($id) { $this->id = $id; }
    public function setName($name) { $this->name = $name; }
    public function setBrand($brand) { $this->brand = $brand; }
    public function setFlavor($flavor) { $this->flavor = $flavor; }
    public function setCategory($category) { $this->category = $category; }
    public function setPrice($price) { $this->price = floatval($price); }
}
