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

    <div class="d-flex flex-column align-items-center justify-content-center w-100 min-vh-100">
        <h1 class="m-2">Ajouter un dossier medical</h1>

        <?php if(!empty($ALERT["message"])): ?>
            <div class="alert <?php echo $ALERT["classValue"]; ?> mt-3" role="alert">
              <p class="font-weight-bold h5"><?php echo $ALERT["message"]; ?></p>
            </div>
        <?php endif; ?>

    </div>

<!-- END OF MY TEMPLATE -->
<?php require_once('inc/footer.php'); ?>