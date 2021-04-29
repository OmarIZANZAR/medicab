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
    if(isset($_POST["searchCons"])){
        # FETCHING SEARCHED CONSULTATIONS:
        $text = $_POST["searchText"];

        if(empty($text)){
            $search_query ="SELECT * FROM consultations ORDER BY date DESC";
        }else{
            $search_query = "SELECT * FROM consultations WHERE patient='$text' OR doctor='$text'";
        }

        $result = $conn->query($search_query);
        if ($result->num_rows > 0) {
            $consultations = $result->fetch_all(MYSQLI_ASSOC);
            $result->free_result();
        }

    } else {
        # FETCHING CONSULTATIONS:
        $fetch_query = "SELECT * FROM consultations ORDER BY date DESC";
        $result = $conn->query($fetch_query);

        if( $result->num_rows > 0){
            $consultations = $result->fetch_all(MYSQLI_ASSOC);
            $result->free_result();
        }
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
        <h2>List des consultations</h2>

        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="d-flex align-items-center jutify-content-center">
            <input type="text" class="form-control" name="searchText" placeholder="chercher une consultation">
            <button class="btn btn-secondary ml-1" type="submit" name="searchCons">Rechercher</button>
        </form>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModal">ajouter une consultation</button>
    </header>

    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">patient</th>
                <th scope="col">médcin</th>
                <th scope="col">date</th>
                <th scope="col">temps</th>
                <th scope="col">durée</th>
                <th></th>
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
                        <td>
                            <form method="POST" action="<?php echo $_SERVER["PHP_SELF"];?>" class="p-0 m-0 w-auto h-auto d-inline">
                                <input type="hidden" name="delete_id" value="<?php echo $consultation["id"];?>">
                                <button class="btn btn-danger" type="submit" name="deleteCon">supprimer</button>
                            </form>
                        </td>
                    </tr>

                <?php endforeach; ?>
            <?php else: ?>
                <div class="d-flex align-items-center justify-content-center p-5">
                    <h5>pas de consultation</h5>
                </div>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- add consultation modal -->
    <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="form" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Ajouter rendez-vous
                </h5>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">

                    <!-- patient -->
                    <div class="mb-3">
                        <label class="form-label">Patient</label>
                        <select class="form-control" name="patient" value="<?php echo $patient; ?>">
                            <?php if(isset($patients)): ?>
                                <?php foreach($patients as $patient): ?>
                                    <?php $pName = $patient["firstname"] . " " . $patient["lastname"]; ?>
                                    <option value="<?php echo $pName;?>" selected><?php echo $pName;?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- doctor -->
                    <div class="mb-3">
                        <label class="form-label">Médcin</label>
                        <select class="form-control" name="doctor" value="<?php echo $doctor; ?>">
                            <?php if(isset($doctors)): ?>
                                <?php foreach($doctors as $doctor): ?>
                                    <?php $docName = $doctor["firstname"] . " " . $doctor["lastname"]; ?>
                                    <option value="<?php echo $docName;?>" selected><?php echo $docName;?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="col-form-label">Date:</label>
                        <input type="date" class="form-control" name="date">
                    </div>

                    <div class="mb-3">
                        <label class="col-form-label">Temps:</label>
                        <input type="time" class="form-control" name="time">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Durée</label>
                        <select class="form-control" name="duration" value="<?php echo $duration; ?>">
                            <option value="15" selected>15 min</option>
                            <option value="30" >30 min</option>
                            <option value="60" >1 heur</option>
                            <option value="120" >2 heurs min</option>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="addCon" for="add-form" class="btn btn-danger">Envoyer</button>
                    </div>

                </form>
            </div>

        </div>
        </div>
    </div>

<!-- END OF MY TEMPLATE -->
<?php require_once('inc/footer.php'); ?>
