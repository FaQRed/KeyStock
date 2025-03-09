<?php include '../header.php';
include_once __DIR__ . '/../database/product_repo/ProductRepository.php';
include_once __DIR__ . '/../database/category_repo/CategoryRepository.php';
include_once __DIR__ . '/../database/review_repo/ReviewRepository.php';
include_once __DIR__ . '/../database/user_repo/UserRepository.php';
include_once __DIR__ . '/../entities/Product.php';
include_once __DIR__ . '/../entities/User.php';
include_once __DIR__ . '/../entities/Review.php';
include_once __DIR__ . '/../entities/Category.php';
$reviewRepository = new ReviewRepository();
$userRepository = new UserRepository();
$categoryRepository = new \category_repo\CategoryRepository();
$productRepository = new ProductRepository();
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if(isset($_SESSION['role'])){
    $userId = $_SESSION['user_id'];
}


$product = $productRepository->read($product_id);

$categoryName = $categoryRepository->read($product->getCategoryId())->getName();
$rating = $reviewRepository->getProductAverageRating($product_id);
$reviews = $reviewRepository->readByProductId($product_id);
$recommendedProducts = $productRepository->getProductsByCategory($product->getCategoryId(), $product->getId());

if (!$product) {
    $_SESSION['error'] = "Product not found.";
    header("Location: ../index.php");
    exit();
}
?>

<div class="wrapper">
    <?php include '../navBar.php'; ?>
    <main class="container mt-5">
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        ?>

        <div class="row">

            <div class="col-md-6">
                <img src="/images/<?php echo $product->getImage(); ?>" class="img-fluid" alt="<?php echo $product->getName(); ?>">
            </div>

            <div class="col-md-6">
                <h2><?php echo $product->getName(); ?></h2>
                <table class="table table-bordered">
                    <tr>
                        <th>Category</th>
                        <td><?php echo $categoryName ?></td>
                    </tr>
                    <tr>
                        <th>Price</th>
                        <td><?php echo $product->getPrice(); ?> PLN</td>
                    </tr>
                    <tr>
                        <th>Manufacturer</th>
                        <td><?php echo $product->getManufacturer(); ?></td>
                    </tr>
                    <tr>
                        <th>Weight</th>
                        <td><?php echo $product->getWeight(); ?> g</td>
                    </tr>
                    <tr>
                        <th>Dimensions</th>
                        <td><?php echo $product->getDimensions(); ?></td>
                    </tr>
                </table>
                <p><?php echo $product->getDescription(); ?></p>
                <form action="/cart/add_to_cart.php" method="post">
                    <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>">
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1">
                    </div>
                    <button type="submit" class="btn_product">Add to Cart</button>
                </form>
            </div>
        </div>


        <div class="reviews mt-5">

            <?php if (count($reviews) > 0): ?>
                <h3>Customer Reviews</h3>
                <h4>Rating: <?php echo number_format($rating, 2);?></h4>
                <br>
                <?php foreach ($reviews as $review):
                    $reviewUsername = $userRepository->read($review->getUserId())->getUsername();
                    ?>
                    <div class="review">
                        <h5><?php echo $reviewUsername ?> <small>(<?php echo $review->getCreatedAt(); ?>)</small></h5>
                        <p>Rating: <?php echo $review->getRating(); ?>/5</p>
                        <p><?php echo $review->getComment(); ?></p>
                    </div>
                    <hr>
                <?php endforeach; ?>
            <?php else: ?>
                <h3>Be the first to leave a review</h3>
                <hr>
                <br>
            <?php endif; ?>

            <h3>Leave a Review</h3>
            <form action="submit_review.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>">
                <input type="hidden" name="user_id" value="<?php
                if(isset($_SESSION['role'])){
                    echo $userId;
                }
                 ?>">
                <div class="form-group">
                    <label for="review">Review:</label>
                    <textarea maxlength="200" class="form-control" id="review" name="review" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="rating">Rating:</label>
                    <select class="form-control" id="rating" name="rating" required>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>
                <button type="submit" class="btn_product">Submit Review</button>
            </form>
        </div>



        <div class="recommended-products mt-5">
            <h3>Recommended Products</h3>
            <div class="row">
                <?php foreach ($recommendedProducts as $recommendedProduct): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <img src="/images/<?php echo $recommendedProduct->getImage(); ?>" class="card-img-top" alt="<?php echo $recommendedProduct->getName(); ?>">
                            <div class="card-body">
                                <h5 class="card-title" style="height: 50px" ><?php echo $recommendedProduct->getName(); ?></h5>
                                <p class="card-text"><?php echo $recommendedProduct->getPrice(); ?> PLN</p>
                                <a href="product.php?id=<?php echo $recommendedProduct->getId(); ?>" class="btn_product">View Product</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>




    </main>
    <?php include '../footer.php'; ?>
</div>
