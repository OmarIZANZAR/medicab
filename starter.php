<?php
    echo Date("d/m/y h:m") . "\n";

    echo strtotime("2021-02-13T18:45") . "\n";

    echo date("d/m/y h:m", strtotime("2021-02-13T18:45") );
?> 