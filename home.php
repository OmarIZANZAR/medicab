<?php
    require('config/envs.php');
    require('config/connectDB.php');
    session_start();

    if(!isset($_SESSION["user"])){
        header("Location: " . ROOT_URL);
    }

    $conn->close();
?>

<?php require_once('inc/header.php'); ?>
<!-- START OF MY TEMPLATE -->

    <div class="mt-5">
        
        <div class="row mx-1 my-3" style="height: 200px">

            <div class="col-md m-1">
                <a class="" href="<?php echo ROOT_URL . "patients.php"; ?>">
                    <div class="card shadow text-white bg-warning w-100 h-100">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <h4 class="card-title text-center font-weight-bolder">Gestion des patients</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md m-1">
                <a class="" href="<?php echo ROOT_URL . "appointements.php"; ?>">
                    <div class="card shadow text-white bg-primary w-100 h-100">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <h4 class="card-title text-center font-weight-bolder">Gestion des rendez-vous</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md m-1">
                <a class="" href="<?php echo ROOT_URL . "consultations.php"; ?>">
                    <div class="card shadow text-white bg-primary w-100 h-100">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <h4 class="card-title text-center font-weight-bolder">Gestion des consultations</h4>
                        </div>
                    </div>
                </a>
            </div>

        </div>

        <div class="row mx-1 my-3" style="height: 200px">

            <div class="col-md m-1">
                <a class="" href="<?php echo ROOT_URL . "ordonnance.php"; ?>">
                    <div class="card shadow text-white bg-primary w-100 h-100">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <h4 class="card-title text-center font-weight-bolder">Imprimer une ordonnance</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md m-1">
                <a class="" href="<?php echo ROOT_URL . "medicDocs.php"; ?>">
                    <div class="card shadow text-white bg-success w-100 h-100">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <h4 class="card-title text-center font-weight-bolder">Dossiers médicaux des patients</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md m-1">
                <a class="" href="<?php echo ROOT_URL . "analyse.php"; ?>">
                    <div class="card shadow text-white bg-warning w-100 h-100">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <h4 class="card-title text-center font-weight-bolder">Analyse budgétaire</h4>
                        </div>
                    </div>
                </a>
            </div>

        </div>

    </div>

<!-- END OF MY TEMPLATE -->
<?php require_once('inc/footer.php'); ?>