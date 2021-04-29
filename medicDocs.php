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

    $patient = "";
    $doctor = "";
    $date = "";
    $time = "";
    $duration = "";

    # ADDING AN APPOINTEMENT:
    if(isset($_POST["addCon"])){
        $patient = mysqli_real_escape_string($conn, $_POST["patient"]);
        $doctor = mysqli_real_escape_string($conn, $_POST["doctor"]);
        $date = mysqli_real_escape_string($conn, $_POST["date"]);
        $time = mysqli_real_escape_string($conn, $_POST["time"]);
        $duration = mysqli_real_escape_string($conn, $_POST["duration"]);

        if( !empty($patient)
            && !empty($doctor)
            && !empty($date)
            && !empty($time)
            && !empty($duration)
        ){
            $create_query = "INSERT INTO consultations (
                patient, 
                doctor, 
                date, 
                time,
                duration
            ) VALUES (
                '$patient',
                '$doctor',
                '$date',
                '$time',
                '$duration'
            )";

            if ($conn->query($create_query) === TRUE) {
                $ALERT["message"] = "consultation added succefuly";
                $ALERT["classValue"] = "alert-success";
            } else {
                $ALERT["message"] = "consultation not added retry later " . $conn->error;
                $ALERT["classValue"] = "alert-danger";
            } 
        }else{
            $ALERT["message"] = "please enter all fields";
            $ALERT["classValue"] = "alert-warning";
        }
    }

    # DELETING AN APPOINTEMENT:
    if(isset($_POST["deleteCon"])){
        $delete_id = $_POST["delete_id"];
        $delete_query = "DELETE FROM consultations WHERE id=$delete_id";

        if ($conn->query($delete_query) === TRUE) {
            $ALERT["message"] = "consultation deleted succefuly";
            $ALERT["classValue"] = "alert-success";
        } else {
            $ALERT["message"] = "consultation not deleted try again";
            $ALERT["classValue"] = "alert-warning";
        }
    }

    # FETCHING PATIENTS AND DOCTORS:
    $patients_query = "SELECT firstname, lastname FROM patients";
    $doctors_query = "SELECT firstname, lastname FROM users WHERE role='doctor'";
    $result1 = $conn->query($patients_query);
    if($result1->num_rows > 0){
        $patients = $result1->fetch_all(MYSQLI_ASSOC);
        $result1->free_result();
    }
    $result2 = $conn->query($doctors_query);
    if($result2->num_rows > 0){
        $doctors = $result2->fetch_all(MYSQLI_ASSOC);
        $result2->free_result();
    }

    #FETCHING CONSULTATIONS:
    if(isset($_POST["searchDoc"])){
        # FETCHING SEARCHED CONSULTATIONS:
        $text = $_POST["searchText"];

        // if(empty($text)){
        //     $search_query ="SELECT * FROM registers ORDER BY created_at DESC";
        // }else{
        //     $search_query = "SELECT * FROM registers WHERE patient='$text'";
        // }

        // $result = $conn->query($search_query);
        // if ($result->num_rows > 0) {
        //     $consultations = $result->fetch_all(MYSQLI_ASSOC);
        //     $result->free_result();
        // }

    } else {
        # FETCHING CONSULTATIONS:
        // $fetch_query = "SELECT * FROM registers ORDER BY created_at DESC";
        // $result = $conn->query($fetch_query);

        // if( $result->num_rows > 0){
        //     $consultations = $result->fetch_all(MYSQLI_ASSOC);
        //     $result->free_result();
        // }
    }

    $conn->close();
?>

<?php require_once('inc/header.php'); ?>
<!-- START OF MY TEMPLATE -->

    <?php if(!empty($ALERT["message"])): ?>
        <div class="alert alert-dismissible <?php echo $ALERT["classValue"]; ?> mt-1" role="alert">
            <button type="button" class="close" data-bs-dismiss="alert">&times;</button>
            <p class="font-weight-bold h5"><?php echo $ALERT["message"]; ?></p>
        </div>
    <?php endif; ?>

    <header class="w-100 p-5 d-flex align-items-center justify-content-between bg-light ">
        <h2>List des dossiers médicaux</h2>

        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="d-flex align-items-center jutify-content-center">
            <input type="text" class="form-control" name="searchText" placeholder="chercher une dossier">
            <button class="btn btn-secondary ml-1" type="submit" name="searchDoc">Rechercher</button>
        </form>

        <a class="btn btn-primary" href="<?php echo ROOT_URL."medicDocAdd.php";?>">ajouter un dossier</a>
    </header>

    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($consultations)): ?>
                <?php foreach($consultations as $key => $consultation): ?>
                    <tr>
                        <th scope="row">1</th>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="d-flex align-items-center justify-content-center p-5">
                    <h5>pas de dossier</h5>
                </div>
            <?php endif; ?>
        </tbody>
    </table>

<!-- END OF MY TEMPLATE -->
<?php require_once('inc/footer.php'); ?>
