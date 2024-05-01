<?php

$name    = $_POST["name"];
$status  = $_POST["status"];
$time01  = $_POST["time01"];
$time02  = $_POST["time02"];
$youken  = $_POST["youken"];

var_dump($name);
    
//2. DB接続します
include "funcs.php";
$pdo = db_con();

//３．データ登録SQL作成
$sql = "INSERT INTO wb(name,status,time01,time02,youken)VALUES(:name,:status,:time01,:time02,:youken)";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':name', $name, PDO::PARAM_STR);     //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':status', $status, PDO::PARAM_STR);   //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':time01', $time01, PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':time02', $time02, PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':youken', $youken, PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();

//４．データ登録処理後
if ($status == false) {
    //sqlError($stmt);
    sqlError($stmt);
} else {
    //５．index.phpへリダイレクト
    redirect("index.php");
    }

?>