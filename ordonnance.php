<?php
    require('config/envs.php');
    require('config/connectDB.php');
    session_start();

    if(!isset($_SESSION["user"])){
        header("Location: " . ROOT_URL);
    } elseif($_SESSION["user"]["role"] !== "doctor") {
        header("Location: " . ROOT_URL);
    }

    $ALERT = array(
        "message" => "",
        "classValue" => "",
    );

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
        <h2>Ordonnance</h2>
    </header>

    <div class="w-auto shadow border m-3 rounded d-none" id="ord-paper">
        <div class="w-100 h-100 p-3 d-flex flex-column justify-content-between">
            <div class="ord-header w-100 d-flex align-items-center justify-content-center" style="height: 150px" >
                <img src="./inc/img/logo_cabinet.jpg" alt="ordonnance header" class="h-100">
            </div>
            <div class="ord-body flex-grow-1 p-2">
                <h2 class="text-center font-weight-bolder">ORDONNANCE</h2>
                <p class="h4 mr-5 text-right">Casablanca le: <?php echo date("d/m/y");?></p>
                <div class="p-3" id="medicsData">

                </div>
            </div>
            <div class="ord-footer w-100 d-flex align-items-center justify-content-center" style="height: 150px" >
                <h2 class="text-center font-weight-bolder">Medicab</h2>
            </div>
        </div>
    </div>

    <div id="formInputs">
        <div>
            <div class="mb-3">
                <label class="form-label">Nom du médicament</label>
                <input type="text" class="form-control" name="medic" placeholder="nom du médicament" id="name">
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">description</label>
                <input type="text" class="form-control" name="description" placeholder="description" id="descr">
            </div>
        </div>
    
    </div>


    <button class="btn btn-primary" id="adder">ajouter un médicament</button>
    <button class="btn btn-success" id="printer">IMPRIMER</button>

    <script>
        const formInputs = document.getElementById("formInputs")
        const ordPaper = document.getElementById("ord-paper")
        const medicsData = document.getElementById("medicsData")

        // ADD MORE FIELDS:
        document.getElementById("adder").addEventListener("click", () => {
            console.log(formInputs.children.length);
            const inputGroup = document.createElement("div")

            inputGroup.innerHTML = `
                <div class="mb-3">
                    <label class="form-label">Nom du médicament ${formInputs.children.length+1}</label>
                    <input type="text" class="form-control" name="medic${formInputs.children.length+1}" placeholder="nom du médicament" id="name">
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">description ${formInputs.children.length+1}</label>
                    <input type="text" class="form-control" name="description" placeholder="description" id="descr">
                </div>`

            formInputs.appendChild(inputGroup)
        })

        // PRINT:
        document.getElementById("printer").addEventListener("click", () => {
            const Inputs = document.querySelectorAll("#name")
            const Descrs = document.querySelectorAll("#descr")

            for(let i = 0; i < Inputs.length; i++){
                const Name = Inputs[i].value
                const Desc = Descrs[i].value

                const div = document.createElement('div')
                div.className = "w-100 m-1 h4"
                div.innerHTML = `
                    <p><strong>${Name}</strong></p>
                    <p>${Desc}</p>
                `
                medicsData.appendChild(div)
            }

            const page = window.document.body.innerHTML
            window.document.body.innerHTML = ordPaper.innerHTML;
            window.print();
            window.document.body.innerHTML = page;
            location.reload()
        })
        

    </script>

<!-- END OF MY TEMPLATE -->
<?php require_once('inc/footer.php'); ?>