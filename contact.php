<?php
include 'header.php';
?>


    <div class="wrapper">
        <?php include 'navBar.php'; ?>
        <main class="container mt-5">
            <div class="row">
                <div class="col-md-6">
                    <h3>Our Contact Information</h3>
                    <p><strong>Address:</strong> Gdańsk,Targ Drzewny 9/11, 80-894</p>
                    <p><strong>Phone:</strong> +48 576 462 078</p>
                    <p><strong>Email:</strong> contact@keystock.xyz</p>
                </div>
                <div class="col-md-6">
                    <h3>Find Us Here</h3>
                    <div id="map" style="width: 100%; height: 400px"></div>
                </div>
            </div>
        </main>
        <?php include 'footer.php'; ?>
    </div>

    <script type="text/javascript">
        ymaps.ready(init);
        function init(){

            let myMap = new ymaps.Map("map", {

                center: [54.35245369062766,18.64765649890054],

                zoom: 18
            });
        }
    </script>

