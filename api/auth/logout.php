<?php
    ini_set("error_reporting",0);
    ini_set("display_errors","Off"); // On : open, O
    session_start();
    unset($_SESSION["precepts_username"]);
    unset($_SESSION["precepts_account"]);
    unset($_SESSION["precepts_username"]);
    unset($_SESSION["precepts_userlevel"]);
    unset($_SESSION["precepts_auth"]);
    unset($_SESSION["precepts_key"]);
    unset($_SESSION["precepts_area"]);
    unset($_SESSION["precepts_expire"]);

    header("Location: ../../pages/index.html");
    /*
    $data[]=Array("AAA","BBB","CCC","DDD","EEE","FFF");
    $data[]=Array("AAA","BBB","CCC","DDD","EEE","FFF");
    $data[]=Array("AAA","BBB","CCC","DDD","EEE","FFF");
    $data[]=Array("AAA","BBB","CCC","DDD","EEE","FFF");
    $data[]=Array("AAA","BBB","CCC","DDD","EEE","FFF");
    $data[]=Array("AAA","BBB","CCC","DDD","EEE","FFF");
    $data[]=Array("AAA","BBB","CCC","DDD","EEE","FFF");
    $data[]=Array("AAA","BBB","CCC","DDD","EEE","FFF");
    $data[]=Array("AAA","BBB","CCC","DDD","EEE","FFF");
    $data[]=Array("AAA","BBB","CCC","DDD","EEE","FFF");

    $json_data = array(
                "draw"            => $draw,
                "recordsTotal"    => 300,
                "recordsFiltered" => 300,
                "data"            => $data
            );
   header("Content-type: application/json");
   //header("Content-Type: text/html; charset=utf-8");
   echo json_encode($json_data);  */
?>

