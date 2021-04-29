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

    $firstname = "";
    $lastname = "";
    $email = "";
    $phone = "";
    $address = "";
    $postal_code = "";
    $gendre = "";
    $social_security = "";
    $associated_doctor = "";
    $illness = "";
    $birth_date = "";
    $description = "";

    # DELETING A PATIENT:
    if(isset($_POST["deletePatient"])){
        $delete_id = $_POST["delete_id"];
        $delete_query = "DELETE FROM patients WHERE id=$delete_id";

        if ($conn->query($delete_query) === TRUE) {
            header("Location: " . ROOT_URL . "patients.php");
        } else {
            $ALERT["message"] = "patient not deleted try again";
            $ALERT["classValue"] = "alert-warning";
        }
    }

    # FETCHING PATIENT:
    $id = $_GET["id"];
    $find_query = " SELECT * FROM patients WHERE id=$id";
    $result = $conn->query($find_query);

    if ($result->num_rows > 0) {
        $p = $result->fetch_all(MYSQLI_ASSOC);
        $patient = $p[0];
        $result->free_result();
    }

    # FETCHING CONSULTATIONS:
    $filter = $patient["firstname"] . " " . $patient["lastname"] ;
    $fetch_query = "SELECT * FROM consultations WHERE patient='$filter' ORDER BY date DESC";
    $result = $conn->query($fetch_query);

    if( $result->num_rows > 0){
        $consultations = $result->fetch_all(MYSQLI_ASSOC);
        $result->free_result();
    }

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
        <a class="btn btn-secondary" href="<?php echo ROOT_URL . "patients.php"; ?>">Go back</a>

        <a class="btn btn-primary" href="<?php echo ROOT_URL . "medicalDoc.php/doc?id=" . $patient["medical_register"]; ?>">Voire dossier médical</a>

        <div>
            <a class="btn btn-warning" href="<?php echo ROOT_URL. "editPatient.php/edit?id=" .$patient["id"]; ?>">modifier</a>

            <form method="POST" action="<?php echo $_SERVER["PHP_SELF"];?>" class="p-0 m-0 w-auto h-auto d-inline">
                <input type="hidden" name="delete_id" value="<?php echo $patient["id"];?>">
                <button class="btn btn-danger" type="submit" name="deletePatient">supprimer</button>
            </form>
        </div>

    </header>

    <h2>Patient <strong class="text-capitalize"><?php echo $patient["firstname"] . " " . $patient["lastname"];?></strong></h2>

    <table class="table table-striped">
        <tbody class="w-75">
            <tr>
                <th scope="row">Nom</th>
                <td class="text-capitalize"><?php echo $patient["firstname"]; ?></td>
            </tr>
            <tr>
                <th scope="row">Prénom</th>
                <td class="text-capitalize"><?php echo $patient["lastname"]; ?></td>
            </tr>
            <tr>
                <th scope="row">Date de naissance</th>
                <td><?php echo $patient["birth_date"]; ?></td>
            </tr>
            <tr>
                <th scope="row">Civilité</th>
                <td><?php echo $patient["gendre"]; ?></td>
            </tr>
            <tr>
                <th scope="row">Adresse</th>
                <td><?php echo $patient["address"]; ?></td>
            </tr>
            <tr>
                <th scope="row">Code postal</th>
                <td><?php echo $patient["postal_code"]; ?></td>
            </tr>
            <tr>
                <th scope="row">Email</th>
                <td><?php echo $patient["email"]; ?></td>
            </tr>
            <tr>
                <th scope="row">Phone</th>
                <td><?php echo $patient["phone"]; ?></td>
            </tr>
            <tr>
                <th scope="row">Médcin associé</th>
                <td><?php echo $patient["associated_doctor"]; ?></td>
            </tr>
            <tr>
                <th scope="row">Securité social</th>
                <td><?php echo $patient["social_security"]; ?></td>
            </tr>
            <tr>
                <th scope="row">Maladie</th>
                <td><?php echo $patient["illness"]; ?></td>
            </tr>
        </tbody>
    </table>

    <div class="m-2">
        <h2>Description:</h2>
        <p><?php echo $patient["description"]; ?></p>
    </div>

    <div class="m-2">
        <h2>Consultation:</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">patient</th>
                    <th scope="col">médcin</th>
                    <th scope="col">date</th>
                    <th scope="col">temps</th>
                    <th scope="col">durée</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($consultations)): ?>
                    <?php foreach($consultations as $key => $consultation): ?>

                        <tr>
                            <th scope="row"><?php echo $key+1; ?></th>
                            <td class="text-capitalize"><?php echo $consultation["patient"]; ?></td>
                            <td class="text-capitalize"><?php echo $consultation["doctor"]; ?></td>
                            <td><?php echo $consultation["date"]; ?></td>
                            <td><?php echo $consultation["time"]; ?></td>
                            <td><?php echo $consultation["duration"] . " min"; ?></td>
                        </tr>

                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center p-5">
                        <h5>pas de consultation</h5>
                    </div>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

<!-- END OF MY TEMPLATE -->
<?php require_once('inc/footer.php'); ?>
