<?php

require("connection.php");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // echo "post";
    $content = (file_get_contents("php://input"));
    // echo $content;
    $decoded = json_decode($content);
    // var_dump($decoded);
    // object(stdClass)#2 (3) { 
    // ["title"]=> string(2) "22" 
    // ["rating"]=> string(3) "222" 
    // ["type"]=> string(3) "add" 
    // }
    // echo $decoded->text;
    // echo file_get_contents("php://input");
    // echo json_encode($_POST);

    if ($decoded->type == "add") {

        $movie = $decoded->movie;
        $rating = $decoded->rating;

        $sql = <<< EOD
            INSERT INTO movies( 
                id,
                title, 
                rating                
            )
            values(null,                
                :title,
                :rating             
            )
            EOD;

        // var_dump($sql);        

        $stmt = $conn->prepare($sql);
        $stmt->execute(array(
            "title" => $movie,
            "rating" => $rating
        ));

        // echo 11;

        if ($conn->lastInsertId()) {
            echo json_encode(["type" => "add", "status" => "success", "lastId" => $conn->lastInsertId()]);
        } else {
            echo json_encode(["type" => "add", "status" => "error",]);
        }
    } else if ($decoded->type == "search") {
        // echo "saerch";

        $term = $decoded->term;
        $selectedVal = $decoded->selectedVal;

        $sql = <<< EOD
            select 
                * 
            from
                products
            where $selectedVal like '%{$term}%'
        EOD;

        // var_dump($sql);

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($rows);
    }
}
