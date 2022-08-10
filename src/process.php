<?php

// require("connection.php");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // echo "post";
    $content = (file_get_contents("php://input"));
    // echo $content;
    $decoded = json_decode($content);
    // var_dump($decoded);    

    if ($decoded->type == "add") {
        if (1) {
            echo json_encode(["type" => "add", "status" => "success",]);
        } else {
            echo json_encode(["type" => "add", "status" => "error",]);
        }
    }
}
