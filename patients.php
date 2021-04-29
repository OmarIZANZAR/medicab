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
    
    if(isset($_POST["searchPatient"])){
        # FETCHING SEARCHED PATIENT:
        $text = $_POST["searchText"];

        if(empty($text)){
            $search_query ="SELECT id, firstname, lastname, email, phone, address FROM patients ";
        }else{
            $search_query = "SELECT 
                id, 
                firstname, 
                lastname, 
                email, 
                phone, 
                address 
            FROM patients WHERE 
                firstname='$text' 
                OR lastname='$text'
            ";
        }

        $result = $conn->query($search_query);

        if ($result->num_rows > 0) {
            $patients = $result->fetch_all(MYSQLI_ASSOC);
            $result->free_result();
        }

    } else {
        # FETCHING PATIENTS:
        $find_query = " SELECT id, firstname, lastname, email, phone, address FROM patients ";
        $result = $conn->query($find_query);

        if ($result->num_rows > 0) {
            $patients = $result->fetch_all(MYSQLI_ASSOC);
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
        <h2>List des patients</h2>

        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="d-flex align-items-center jutify-content-center">
            <input type="text" class="form-control" name="searchText" placeholder="chercher un patient">
            <button class="btn btn-secondary ml-1" type="submit" name="searchPatient">Rechercher</button>
        </form>

        <a class="btn btn-primary" href="<?php echo ROOT_URL . "addPatient.php"; ?>">ajouter un patient</a>
    </header>

    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">nom et prénom</th>
                <th scope="col">phone</th>
                <th scope="col">email</th>
                <th scope="col">address</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($patients)): ?>
                <?php foreach($patients as $key => $patient): ?>
                    <tr>
                        <th scope="row"><?php echo $key+1; ?></th>
                        <td class="text-capitalize"><?php echo $patient["firstname"] . " " . $patient["lastname"]; ?></td>
                        <td><?php echo $patient["phone"]; ?></td>
                        <td><?php echo $patient["email"]; ?></td>
                        <td><?php echo $patient["address"]; ?></td>
                        <td>
                            <a class="btn btn-secondary" href="<?php echo ROOT_URL . "patient.php/patient?id=" . $patient["id"]; ?>">voire plus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="d-flex align-items-center justify-content-center p-5">
                    <h5>pas de patient</h5>
                </div>
            <?php endif; ?>
        </tbody>
    </table>   

<!-- END OF MY TEMPLATE -->
<?php require_once('inc/footer.php'); ?>
