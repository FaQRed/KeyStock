<?php

namespace category_repo;

interface CategoryRepositoryInterface
{
    public function create($category_name);
    public function read($id);
    public function update(\Category $category);
    public function delete($id);
    public function readAll();
    public function exists($name);
    public function hasProducts($categoryId);
}