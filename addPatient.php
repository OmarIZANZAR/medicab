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

    # ADDING A PATIENT:
    if(isset($_POST["addPatient"])){
        $firstname = mysqli_real_escape_string($conn, $_POST["firstname"]);
        $lastname = mysqli_real_escape_string($conn, $_POST["lastname"]);
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $phone = mysqli_real_escape_string($conn, $_POST["phone"]);
        $birth_date = mysqli_real_escape_string($conn, $_POST["birth_date"]);
        $address = mysqli_real_escape_string($conn, $_POST["address"]);
        $postal_code = mysqli_real_escape_string($conn, $_POST["postal_code"]);
        $social_security = mysqli_real_escape_string($conn, $_POST["social_security"]);
        $gendre = mysqli_real_escape_string($conn, $_POST["gendre"]);
        $associated_doctor = mysqli_real_escape_string($conn, $_POST["associated_doctor"]);
        $illness = mysqli_real_escape_string($conn, $_POST["illness"]);
        $description = mysqli_real_escape_string($conn, $_POST["description"]);

        if( !empty($firstname)
            && !empty($lastname)
            && !empty($email)
            && !empty($phone)
            && !empty($birth_date)
            && !empty($address)
            && !empty($postal_code)
            && !empty($social_security)
            && !empty($gendre)
            && !empty($associated_doctor)
            && !empty($illness)
        ){

            if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){

                $create_query = "INSERT INTO patients (
                    firstname,
                    lastname,
                    email,
                    phone,
                    address,
                    postal_code,
                    gendre,
                    social_security,
                    associated_doctor,
                    illness,
                    birth_date,
                    description,
                    medical_register
                ) VALUES (
                    '$firstname',
                    '$lastname',
                    '$email',
                    '$phone',
                    '$address',
                    '$postal_code',
                    '$gendre',
                    '$social_security',
                    '$associated_doctor',
                    '$illness',
                    '$birth_date',
                    '$description',
                    1
                )";

                if ($conn->query($create_query) === TRUE) {
                    $ALERT["message"] = "patient added succefuly";
                    $ALERT["classValue"] = "alert-success";
                } else {
                    $ALERT["message"] = "patient not added retry later";
                    $ALERT["classValue"] = "alert-danger";
                } 
                
            }else{
                $ALERT["message"] = "please enter a valid email";
                $ALERT["classValue"] = "alert-warning";
            }

        }else{
            $ALERT["message"] = "please enter all fields";
            $ALERT["classValue"] = "alert-warning";
        }
    }

    $find_query = "SELECT firstname, lastname FROM users WHERE role='doctor'";
    $result = $conn->query($find_query);

    if ($result->num_rows > 0) {
        $doctors = $result->fetch_all(MYSQLI_ASSOC);
        $result->free_result();
    }

    $conn->close();
?>

<?php require_once('inc/header.php'); ?>
<!-- START OF MY TEMPLATE -->

    <div class="d-flex flex-column align-items-center justify-content-center w-100 min-vh-100">
        <h1 class="m-2">Register Patient</h1>

        <?php if(!empty($ALERT["message"])): ?>
            <div class="alert <?php echo $ALERT["classValue"]; ?> mt-3" role="alert">
              <p class="font-weight-bold h5"><?php echo $ALERT["message"]; ?></p>
            </div>
        <?php endif; ?>

        <form class="w-auto shadow p-3 m-3 border rounded" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" autocomplete="true">

            <!-- firstname & lastname -->
            <div class="row mb-3">
                <div class="col">
                    <label for="firstname" class="form-label">Nom</label>
                    <input type="text" class="form-control" name="firstname" placeholder="Nom" value="<?php echo $firstname; ?>">
                </div>

                <div class="col">
                    <label for="lastname" class="form-label">Prénom</label>
                    <input type="text" class="form-control" name="lastname" placeholder="Prénom" value="<?php echo $lastname; ?>">
                </div>
            </div>

            <!-- birth date -->
            <div class="mb-3">
              <label for="birth_date" class="form-label">date de naissance</label>
              <input type="date" class="form-control" name="birth_date" value="<?php echo $firstname; ?>">
            </div>

            <!-- address -->
            <div class="mb-3">
              <label for="address" class="form-label">Adresse</label>
              <input type="text" class="form-control" name="address" placeholder="Adresse" value="<?php echo $lastname; ?>">
            </div>

            <!-- postal code -->
            <div class="mb-3">
              <label for="postal_code" class="form-label">Code postal</label>
              <input type="text" class="form-control" name="postal_code" placeholder="00000" value="<?php echo $postal_code; ?>">
            </div>

            <!-- email -->
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" name="email" placeholder="abc@email.xyx" value="<?php echo $email; ?>">
            </div>

            <!-- phone -->
            <div class="mb-3">
              <label class="form-label">Téléphone</label>
              <input type="text" class="form-control" name="phone" placeholder="+212606060606" value="<?php echo $phone; ?>">
            </div>

            <!-- gendre -->
            <div class="row m-0 mb-3 p">
                <label class="form-label mr-4">Civilité</label>
                <div class="form-check mr-3">
                    <input class="form-check-input" type="radio" name="gendre" value="homme" checked>
                    <label class="form-check-label">
                        homme
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gendre" value="femme">
                    <label class="form-check-label">
                        femme
                    </label>
                </div>
            </div>

            <!-- social security -->
            <div class="mb-3">
              <label class="form-label">Sécurité sociale</label>
              <input type="text" class="form-control" name="social_security" placeholder="L12345" value="<?php echo $social_security; ?>">
            </div>

            <!-- associated doctor -->
            <div class="mb-3">
                <label class="form-label">Médcin associé</label>
                <select class="form-control" name="associated_doctor" value="<?php echo $associated_doctor; ?>">
                    <?php if(isset($doctors)): ?>
                        <?php foreach($doctors as $doctor): ?>
                            <?php $docName = $doctor["firstname"] . " " . $doctor["lastname"]; ?>
                            <option value="<?php echo $docName;?>" selected><?php echo $docName;?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- illness -->
            <div class="mb-3">
                <label class="col-form-label">cas ou maladie:</label>
                <input type="text" class="form-control" name="illness" placeholder="cas ou maladie" value="<?php echo $illness;?>" >
            </div>

            <!-- description -->
            <div class="mb-3">
                <label for="description" class="col-form-label">description:</label>
                <textarea class="form-control" name="description" placeholder="detail"><?php echo $description;?></textarea>
            </div>

            <div class="row">
                <div class="col">
                    <a class="btn btn-danger w-100" href="<?php echo ROOT_URL . "patients.php"; ?>">ANNULER</a>
                </div>
                <div class="col">
                    <button type="submit" name="addPatient" class="btn btn-success w-100">ENVOYER</button>
                </div>
            </div>

        </form>

    </div>

<!-- END OF MY TEMPLATE -->
<?php require_once('inc/footer.php'); ?>