<?php
    require('config/envs.php');
    require('config/connectDB.php');
    session_start();

    $ALERT = array(
        "message" => "",
        "classValue" => "",
    );

    $firstname = "";
    $lastname = "";
    $email = "";
    $phone = "";
    $birth_date = "";
    $address = "";
    $postal_code = "";
    $role = "";
    $user_name = "";
    $password = "";

    if($_POST){
        $firstname = mysqli_real_escape_string($conn, $_POST["firstname"]);
        $lastname = mysqli_real_escape_string($conn, $_POST["lastname"]);
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $phone = mysqli_real_escape_string($conn, $_POST["phone"]);
        $birth_date = mysqli_real_escape_string($conn, $_POST["birth_date"]);
        $address = mysqli_real_escape_string($conn, $_POST["address"]);
        $postal_code = mysqli_real_escape_string($conn, $_POST["postal_code"]);
        $role = mysqli_real_escape_string($conn, $_POST["role"]);
        $user_name = mysqli_real_escape_string($conn, $_POST["user_name"]);
        $password = mysqli_real_escape_string($conn, $_POST["password"]);

        if( !empty($firstname)
            && !empty($lastname)
            && !empty($email)
            && !empty($phone)
            && !empty($birth_date)
            && !empty($address)
            && !empty($postal_code)
            && !empty($role)
            && !empty($user_name)
            && !empty($password)
        ){
            if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){

                $find_query = " SELECT * FROM users WHERE user_name='$user_name' ";
                $result = $conn->query($find_query);

                if ($result->num_rows > 0) {
                    $result->free_result();

                    $ALERT["message"] = "user name already taken choose another user name";
                    $ALERT["classValue"] = "alert-warning";
                } else {
                    $create_query = "INSERT INTO users (
                        firstname, 
                        lastname, 
                        email, 
                        phone, 
                        birth_date, 
                        address, 
                        postal_code,
                        role,
                        user_name,
                        password
                    ) VALUES (
                        '$firstname',
                        '$lastname',
                        '$email',
                        '$phone',
                        '$birth_date',
                        '$address',
                        '$postal_code',
                        '$role',
                        '$user_name',
                        '$password'
                    )";
    
                    if ($conn->query($create_query) === TRUE) {
                        $ALERT["message"] = "user added succefuly";
                        $ALERT["classValue"] = "alert-success";
                    } else {
                        $ALERT["message"] = "user not added retry later";
                        $ALERT["classValue"] = "alert-danger";
                    } 
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

    $conn->close();
?>

<?php require_once('inc/header.php'); ?>
<!-- START OF MY TEMPLATE -->

    <div class="d-flex flex-column align-items-center justify-content-center w-100 min-vh-100">
        <h1 class="m-2">Medicab</h1>
        <p class="m-1">Ajouter un utilisateur</p>

        <?php if(!empty($ALERT["message"])): ?>
            <div class="alert <?php echo $ALERT["classValue"]; ?> mt-3" role="alert">
              <p class="font-weight-bold h5"><?php echo $ALERT["message"]; ?></p>
            </div>
            <?php if($ALERT["classValue"] === "alert-success" ): ?>
                <a class="btn btn-outline-info" href="<?php echo ROOT_URL; ?>">Aller vers Login</a>
            <?php endif; ?>
        <?php endif; ?>

        <form class="w-auto shadow p-3 m-3 border rounded" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" autocomplete="true">

            <div class="row mb-3">
                <div class="col">
                    <label for="firstname" class="form-label">Nom</label>
                    <input type="text" class="form-control" name="firstname" placeholder="Nom" value="<?php echo $firstname; ?>">
                </div>

                <div class="col">
                    <label for="lastname" class="form-label">Prenom</label>
                    <input type="text" class="form-control" name="lastname" placeholder="Prenom" value="<?php echo $lastname; ?>">
                </div>
            </div>

            <div class="mb-3">
              <label for="birth_date" class="form-label">date de naissance</label>
              <input type="date" class="form-control" name="birth_date" value="<?php echo $firstname; ?>">
            </div>

            <div class="mb-3">
              <label for="address" class="form-label">Adresse</label>
              <input type="text" class="form-control" name="address" placeholder="adresse" value="<?php echo $lastname; ?>">
            </div>

            <div class="mb-3">
              <label for="postal_code" class="form-label">code postal</label>
              <input type="text" class="form-control" name="postal_code" placeholder="00000" value="<?php echo $postal_code; ?>">
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" name="email" placeholder="abc@email.xyx" value="<?php echo $email; ?>">
            </div>

            <div class="mb-3">
              <label for="phone" class="form-label">Mobile</label>
              <input type="text" class="form-control" name="phone" placeholder="+212606060606" value="<?php echo $phone; ?>">
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Rôle / Poste / Travail</label>
                <select class="form-control" name="role" value="<?php echo $role; ?>">
                    <option value="system manager" selected>system manager</option>
                    <option value="doctor">médcin(e)</option>
                    <option value="assistant doctor">médcin(e) assistant(e)</option>
                    <option value="nurse">infirmier(e)</option>
                </select>
            </div>

            <div class="mb-3">
              <label for="user_name" class="form-label">Nom de l'utilisateur</label>
              <input type="text" class="form-control" name="user_name" placeholder="User Name" value="<?php echo $user_name; ?>">
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Mot de passe</label>
              <input type="password" class="form-control" name="password" placeholder="Mot de passe">
            </div>

            <div class="row">
                <div class="col">
                    <a class="btn btn-danger w-100" href="<?php echo ROOT_URL; ?>">Annuler</a>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-success w-100">Envoyer</button>
                </div>
            </div>

        </form>

        <a class="btn btn-outline-info" href="<?php echo ROOT_URL; ?>">Aller vers Login</a>
    </div>

<!-- END OF MY TEMPLATE -->
<?php require_once('inc/footer.php'); ?>