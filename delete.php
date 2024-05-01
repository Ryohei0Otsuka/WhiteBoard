<?php

$namedlt = $_POST["name_dlt"];

include "funcs.php";
$pdo = db_con();

$stmt = $pdo->prepare('DELETE FROM wb WHERE name =:name');
$stmt->bindValue(':name', $namedlt, PDO::PARAM_STR);
$status = $stmt->execute();

if ($status == false) {
    //sqlError($stmt);
    sqlError($stmt);
} else {
    //index.phpへリダイレクト
    redirect("index.php");
    }
?>