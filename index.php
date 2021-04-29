<?php
  require('config/envs.php');
  require('config/connectDB.php');
  session_start();

  if(isset($_SESSION["user"])){
    header("Location: " . ROOT_URL . "home.php");
  }

  $ALERT = array(
    "message" => "",
    "classValue" => "",
  );

  $user_name = "";
  $password = "";

  if($_POST){
    $user_name = mysqli_real_escape_string($conn, $_POST["user_name"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    if( !empty($user_name) && !empty($password) ){

        $find_query = " SELECT * FROM users WHERE user_name='$user_name' ";
        $result = $conn->query($find_query);

        if ($result->num_rows > 0) {
            $registered_user = $result->fetch_assoc();
            
            if( $registered_user["password"] === $password ){
              $result->free_result();

              $_SESSION["user"] = $registered_user;

              header("Location: " . ROOT_URL . "home.php");
            }else{
              $ALERT["message"] = "wrong user name or password ";
              $ALERT["classValue"] = "alert-warning";
            }

        } else {
          $ALERT["message"] = "wrong user name or password ";
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
          <p class="m-1">Login pour accéder au systeme</p>

          <?php if(!empty($ALERT["message"])): ?>
            <div class="alert <?php echo $ALERT["classValue"]; ?> mt-3" role="alert">
              <p class="font-weight-bold h5"><?php echo $ALERT["message"]; ?></p>
            </div>
          <?php endif; ?>

          <form class="w-25 shadow p-3 m-3 border rounded" method="POST" action="<?php echo $_SERVER["PHP_SELF"];?>">

              <div class="mb-3">
                <label for="user_name" class="form-label">Nom de l'utilisateur</label>
                <input type="text" class="form-control" name="user_name" placeholder="User name" value="">
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" name="password" placeholder="Password" value="">
              </div>

              <button type="submit" class="btn btn-success w-100">Envoyer</button>
          </form>

          <a class="btn btn-outline-info" href="<?php echo ROOT_URL . "register.php";?>" >Ajouter un utilisateur</a>
      </div>

<!-- END OF MY TEMPLATE -->
<?php require_once('inc/footer.php'); ?>