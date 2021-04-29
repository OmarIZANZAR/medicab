<?php
    require('config/envs.php');
    require('config/connectDB.php');
    session_start();

    if(!isset($_SESSION["user"])){
        header("Location: " . ROOT_URL);
    }
    
    $ALERT = array(
        "message" => "",
        "classValue" => "",
    );

    $conn->close();
?>

<?php require_once('inc/header.php'); ?>
<!-- START OF MY TEMPLATE -->

    <?php if(!empty($ALERT["message"])): ?>
        <div class="alert <?php echo $ALERT["classValue"]; ?> mt-3" role="alert">
            <p class="font-weight-bold h5"><?php echo $ALERT["message"]; ?></p>
        </div>
    <?php endif; ?>

    <header class="w-100 p-5 d-flex align-items-center justify-content-between bg-light ">
        <h2>Analyse budgétaire</h2>
    </header>

<!-- END OF MY TEMPLATE -->
<?php require_once('inc/footer.php'); ?>
