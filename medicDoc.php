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
        <a class="btn btn-secondary" href="<?php echo ROOT_URL . "medicDocs.php";?>">Go back</a>

        <div>
            <a class="btn btn-warning" href="<?php echo ROOT_URL. "medicaDoc.php/doc?id="; ?>">modifier</a>

            <form method="POST" action="<?php echo $_SERVER["PHP_SELF"];?>" class="p-0 m-0 w-auto h-auto d-inline">
                <input type="hidden" name="delete_id" value="">
                <button class="btn btn-danger" type="submit" name="deletePatient">supprimer</button>
            </form>
        </div>

    </header>

    <h2>Dossier de </h2>

<!-- END OF MY TEMPLATE -->
<?php require_once('inc/footer.php'); ?>
