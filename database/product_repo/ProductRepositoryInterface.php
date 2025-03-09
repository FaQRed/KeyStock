<?php

interface ProductRepositoryInterface
{
    public function create(Product $product);
    public function read($id);
    public function update(Product $product);
    public function delete($id);
    public function readAll();
    public function readByCategory($id);
    public function getRecentProducts();
    public function getProductsByCategory($category_id, $exclude_product_id = null);
}