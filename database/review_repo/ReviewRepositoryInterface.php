<?php

interface ReviewRepositoryInterface
{
    public function create(Review $review);
    public function read($id);
    public function update(Review $review);
    public function delete($id);
    public function readByProductId($productId);
    public function getProductAverageRating($productId);
    public function readByUserId($user_id);
}
