<?php
include "config.php";
include "utils.php";

$dbConn =  connect($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (isset($_GET['plate']))
    {
      //Mostrar un post
      $sql = $dbConn->prepare("select * from vehicle where plate=:plate");
      $sql->bindValue(':plate', $_GET['plate']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
      exit();
	  }
      else 
      {
        //Mostrar lista de post
        $sql = $dbConn->prepare("select * from vehicle");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode( $sql->fetchAll()  );
        exit();
      }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
      $input = $_POST;
      $sql = "insert into vehicle (brand, model, year, plate, color)      
      values(:brand, :model, :year, :plate, :color)";
      $statement = $dbConn->prepare($sql);
      bindAllValues($statement, $input);
      $statement->execute();
  
      $postCodigo = $dbConn->lastInsertId();
      if($postCodigo)
      {
        $input['plate'] = $postCodigo;
        header("HTTP/1.1 200 OK");
        echo json_encode($input);
        exit();
       }
}


if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
	  $id = $_GET['id'];
    $statement = $dbConn->prepare("delete from vehicle where id=:id");
    $statement->bindValue(':id', $id);
    $statement->execute();
	header("HTTP/1.1 200 OK");
	exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
    $postId = $input['id'];
    $fields = getParams($input);

    $sql = "update vehicle set $fields
          where id='$postId' ";

    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);

    $statement->execute();
    header("HTTP/1.1 200 OK");
    exit();
}

?>
