<?php use category_repo\CategoryRepository;

include '../header.php';
include_once __DIR__ . '/../database/product_repo/ProductRepository.php';
include_once __DIR__ . '/../database/category_repo/CategoryRepository.php';
include_once __DIR__ . '/../entities/Product.php';

$productRepository = new ProductRepository();
$categoryRepository = new CategoryRepository();

$category_id = isset($_GET['category']) ? intval($_GET['category']) : 0;
$price_range = isset($_GET['price']) ? explode('-', $_GET['price']) : [];
$weight_range = isset($_GET['weight']) ? explode('-', $_GET['weight']) : [];

$categories = $categoryRepository->readAll();
$products = $category_id ? $productRepository->readByCategory($category_id) : $productRepository->readAll();

if ($category_id) {
    $products = $productRepository->readByCategory($category_id);
}

if ($price_range) {
    $min_price = isset($price_range[0]) ? intval($price_range[0]) : 0;
    $max_price = isset($price_range[1]) ? intval($price_range[1]) : PHP_INT_MAX;
    $products = array_filter($products, function ($product) use ($min_price, $max_price) {
        return $product->getPrice() >= $min_price && $product->getPrice() <= $max_price;
    });
}

if ($weight_range) {
    $min_weight = isset($weight_range[0]) ? intval($weight_range[0]) : 0;
    $max_weight = isset($weight_range[1]) ? intval($weight_range[1]) : PHP_INT_MAX;
    $products = array_filter($products, function ($product) use ($min_weight, $max_weight) {
        return $product->getWeight() >= $min_weight && $product->getWeight() <= $max_weight;
    });
}



?>

<div class="wrapper">
    <?php include '../navBar.php'; ?>
    <main class="container mt-5">
        <div class="row">

            <aside class="col-md-3">
                <h4>Categories</h4>
                <ul class="list-group">
                    <?php foreach ($categories as $category): ?>
                        <li class="list-group-item">
                            <a class="categories_nav" href="?category=<?php echo $category->getId(); ?>"><?php echo $category->getName(); ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <h4 class="mt-4">Filters</h4>
                <form id="filterForm">
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="text" class="form-control" id="price" name="price" placeholder="Enter price range">
                    </div>
                    <div class="form-group">
                        <label for="weight">Weight</label>
                        <input type="text" class="form-control" id="weight" name="weight" placeholder="Enter weight range">
                    </div>
                    <button type="submit" class="btn_product">Apply Filters</button>
                </form>
            </aside>


            <div class="col-md-9">
                <h2>All Products</h2>
                <div class="form-group">
                    <label for="search"></label><input type="text" id="search" class="form-control" placeholder="Search for products...">
                </div>
                <div class="row" id="productsList">
                    <?php foreach ($products as $product):
                        $categoryName = $categoryRepository->read($product->getCategoryId())->getName()?>
                        <div class="col-md-4 mb-4 product-item" data-category="<?php echo $categoryName ?>" data-price="<?php echo $product->getPrice(); ?>" ">
                            <div class="card">
                                <img src="images/<?php echo $product->getImage(); ?>" class="card-img-top" alt="<?php echo $product->getName(); ?>">
                                <div class="card-body">
                                    <h5 class="card-title" style="height: 50px"><?php echo $product->getName(); ?></h5>
                                    <p class="card-text"><?php echo $product->getPrice(); ?> PLN</p>
                                    <a href="product.php?id=<?php echo $product->getId(); ?>" class="btn_product">View Product</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
    </main>
    <?php include '../footer.php'; ?>
</div>

<script>
        const searchInput = document.getElementById('search');

        searchInput.addEventListener('input', function() {
            const query = searchInput.value.toLowerCase();
            const products = document.querySelectorAll('.product-item');

            products.forEach(function(product) {
                const name = product.querySelector('.card-title').textContent.toLowerCase();
                if (name.includes(query)) {
                    product.style.display = '';
                } else {
                    product.style.display = 'none';
                }
            });
        });
</script>