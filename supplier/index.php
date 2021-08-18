<?php 
session_start();  
if (!isset($_SESSION['supplier_id'])) {
  header("location:login.php");
}

include "./templates/top.php"; 

?>
 
<?php include "./templates/navbar.php"; ?>

<div class="container-fluid">
  <div class="row">
    
    <?php include "./templates/sidebar.php"; ?>
    <main>
      <div class="container-fluid px-4">
        <!-- <h1 class="mt-4">Supplier Dashboard</h1> -->
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div onclick="location.href='./products.php';" class="card-body" style="cursor: pointer;">All Products</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <div class="small text-white"><?php //Get Total Product Number ?></div>
                    </div>
                </div>
            </div>
        </div>
    </main> 
  </div>
</div>

<?php include "./templates/footer.php"; ?>

<script type="text/javascript" src="./js/index.js"></script>
