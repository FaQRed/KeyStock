<?php include 'header.php';
include_once __DIR__ . '/database/product_repo/ProductRepository.php';
include_once __DIR__ . '/entities/Product.php';
$productRepository = new ProductRepository();
?>

<div class="wrapper">
    <?php include 'navBar.php'; ?>
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

        <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="/images/banner1.jpeg" class="d-block w-100" alt="Mechanical Keyboard">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Discover the Best Mechanical Keyboards</h5>
                        <p>Find the perfect mechanical keyboard for your needs.</p>
                    </div>
                </div>
            </div>
        </div>


        <div class="products-section mt-5">
            <h2>Recently Added Products</h2>
            <div class="row">
                <?php
                $recent_products = $productRepository->getRecentProducts();
                foreach ($recent_products as $product): ?>
                    <div class="col-md-3">
                        <div class="card mb-4 shadow-sm">
                            <img src="/images/<?php echo $product->getImage(); ?>" class="card-img-top" alt="<?php echo $product->getName(); ?>">
                            <div class="card-body">
                                <h5 class="card-title" style="height: 50px" ><?php echo $product->getName(); ?></h5>
                                <p class="card-text"><?php echo $product->getPrice(); ?> PLN</p>
                                <a href="/product/product.php?id=<?php echo $product->getId(); ?>" class="btn_product" >View Product</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>


        <div class="other-content mt-5">
            <h2>Our Collections</h2>
            <p>Explore our wide range of mechanical keyboards, switches, and keycaps. We offer a variety of products to suit every need, from gaming to professional use. Check out our latest collections and find the perfect keyboard for you.</p>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</div>
