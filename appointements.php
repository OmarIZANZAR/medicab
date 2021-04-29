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
    $phone = "";
    $date = "";
    $desk = "";
    $description = "";

    # ADDING AN APPOINTEMENT:
    if(isset($_POST["addapt"])){
        $firstname = mysqli_real_escape_string($conn, $_POST["firstname"]);
        $lastname = mysqli_real_escape_string($conn, $_POST["lastname"]);
        $phone = mysqli_real_escape_string($conn, $_POST["phone"]);
        $date = mysqli_real_escape_string($conn, $_POST["date"]);
        $desk = mysqli_real_escape_string($conn, $_POST["desk"]);
        $description = mysqli_real_escape_string($conn, $_POST["description"]);

        if( !empty($firstname)
            && !empty($lastname)
            && !empty($phone)
            && !empty($date)
            && !empty($desk)
        ){
            $create_query = "INSERT INTO appointements (
                firstname, 
                lastname, 
                phone, 
                date,
                desk, 
                description
            ) VALUES (
                '$firstname',
                '$lastname',
                '$phone',
                '$date',
                '$desk',
                '$description'
            )";

            if ($conn->query($create_query) === TRUE) {
                $ALERT["message"] = "appointement added succefuly";
                $ALERT["classValue"] = "alert-success";
            } else {
                $ALERT["message"] = "appointement not added retry later " . $conn->error;
                $ALERT["classValue"] = "alert-danger";
            } 
        }else{
            $ALERT["message"] = "please enter all fields";
            $ALERT["classValue"] = "alert-warning";
        }
    }

    # DELETING AN APPOINTEMENT:
    if(isset($_POST["deleteapt"])){
        $delete_id = $_POST["delete_id"];
        $delete_query = "DELETE FROM appointements WHERE id=$delete_id";

        if ($conn->query($delete_query) === TRUE) {
            $ALERT["message"] = "appointement deleted succefuly";
            $ALERT["classValue"] = "alert-success";
        } else {
            $ALERT["message"] = "appointement not deleted try again";
            $ALERT["classValue"] = "alert-warning";
        }
    }

    if(isset($_POST["searchApt"])){
        # FETCHING SEARCHED PATIENT:
        $text = $_POST["searchText"];

        if(empty($text)){
            $search_query = "SELECT * FROM appointements ORDER BY date ASC";
        }else{
            $search_query = "SELECT * FROM appointements WHERE firstname='$text' OR lastname='$text' ";
        }

        $result = $conn->query($search_query);

        if ($result->num_rows > 0) {
            $appointements = $result->fetch_all(MYSQLI_ASSOC);
            $result->free_result();
        }

    } else {

        # FETCHING APPOINTEMENTS:
        $find_query = " SELECT * FROM appointements ORDER BY date ASC";
        $result = $conn->query($find_query);
        
        if ($result->num_rows > 0) {
            $appointements = $result->fetch_all(MYSQLI_ASSOC);
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
        <h2>Table des rendez-vous</h2>

        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="d-flex align-items-center jutify-content-center">
            <input type="text" class="form-control" name="searchText" placeholder="chercher un rendez-vous">
            <button class="btn btn-secondary ml-1" type="submit" name="searchApt">Rechercher</button>
        </form>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModal">ajouter un rendez vous</button>
    </header>

    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">nom et prénom</th>
                <th scope="col">phone</th>
                <th scope="col">bureau</th>
                <th scope="col">date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($appointements)): ?>
                <?php foreach($appointements as $key => $appointement): ?>
                    <tr>
                        <th scope="row"><?php echo $key+1; ?></th>
                        <td class="text-capitalize"><?php echo $appointement["firstname"] . " " . $appointement["lastname"]; ?></td>
                        <td><?php echo $appointement["phone"]; ?></td>
                        <td><?php echo $appointement["desk"]; ?></td>
                        <td><?php echo date("l, d/m/Y H:i", strtotime($appointement["date"])); ?></td>
                        <td>
                            <button class="btn btn-secondary" data-bs-target="#detailsModal<?php echo $key; ?>" data-bs-toggle="modal">voire plus</button>

                            <form method="POST" action="<?php echo $_SERVER["PHP_SELF"];?>" class="p-0 m-0 w-auto h-auto d-inline">
                                <input type="hidden" name="delete_id" value="<?php echo $appointement["id"];?>">
                                <button class="btn btn-danger" type="submit" name="deleteapt">supprimer</button>
                            </form>
                        </td>

                        <!-- view more modal -->
                        <div class="modal fade" id="detailsModal<?php echo $key; ?>" tabindex="-1" aria-labelledby="details" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">
                                            appointement description:
                                        </h5>

                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <p><?php echo $appointement["description"];?></p>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="d-flex align-items-center justify-content-center p-5">
                    <h5>No appointement set yet</h5>
                </div>
            <?php endif; ?>
        </tbody>
    </table>   

    <!-- add appointement modal -->
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

                    <div class="row mb-3">
                        <div class="col">
                            <label for="firstname" class="form-label">Firstname</label>
                            <input type="text" class="form-control" name="firstname" placeholder="Firstname" value="<?php echo $firstname; ?>">
                        </div>

                        <div class="col">
                            <label for="lastname" class="form-label">Lastname</label>
                            <input type="text" class="form-control" name="lastname" placeholder="Lastname" value="<?php echo $lastname; ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone" placeholder="+212606060606" value="<?php echo $phone; ?>">
                    </div>

                    <div class="mb-3">
                        <label for="desk" class="col-form-label">bureau:</label>
                        <select class="form-control" name="desk" value="<?php echo $desk; ?>">
                            <option value="desk 1" selected>desk 1</option>
                            <option value="desk 2">desk 2</option>
                            <option value="desk 3">desk 3</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="date" class="col-form-label">date:</label>
                        <input type="datetime-local" class="form-control" name="date">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="col-form-label">description:</label>
                        <textarea class="form-control" name="description" placeholder="detail"><?php echo $description;?></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="addapt" for="add-form" class="btn btn-danger">Submit</button>
                    </div>

                </form>
            </div>

        </div>
        </div>
    </div>

<!-- END OF MY TEMPLATE -->
<?php require_once('inc/footer.php'); ?>
