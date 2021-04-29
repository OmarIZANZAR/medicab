<?php

  function Logout(){
    session_unset();
    session_destroy();
    header("Location: " . ROOT_URL);
  }

  if(isset($_POST["logout"]) && isset($_SESSION["user"])){
    Logout();
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- FONT AWESOME STYLE -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />

    <!-- BOOTSTRAP THEME STYLE -->
    <link rel="stylesheet" href="https://bootswatch.com/4/lumen/bootstrap.min.css" >

    <!-- COSTUMAZED STYLE -->
    <link rel="stylesheet" href="<?php echo ROOT_URL . "css/style.css"; ?>" >

    <!-- SITE TITLE -->
    <title>Medicab</title>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light d-flex align-items-center justify-content-between">
    <div>
    <a class="navbar-brand font-weight-bold" href="<?php echo ROOT_URL;?>">Medicab</a>

    <?php if(isset($_SESSION["user"])): ?>
      <a class="btn btn-outline-info ml-3" href="<?php echo ROOT_URL . "register.php";?>">ajouter un utilisateur</a>
    <?php endif; ?>
    
    </div>
    
    <?php if(isset($_SESSION["user"])): ?>
      <div class="d-flex align-items-center justify-content-center">

        <p class="mr-3 mb-0 h-100 h5 text-capitalize">

          <?php echo $_SESSION["user"]["role"] . ", "; ?>

          <span class="font-weight-bolder">
            <?php echo $_SESSION["user"]["firstname"] . " " . $_SESSION["user"]["lastname"]; ?>
          </span>
        </p>

        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
          <button type="submit" class="btn btn-danger" name="logout">LOGOUT</button>
        </form>

      </div>
    <?php endif; ?>

  </nav>

    <div class="container w-100 min-vh-100">