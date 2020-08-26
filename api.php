<?php

//api.php

######################################################
###################################################### Veritabanına Bağlan
######################################################
$connect = new PDO("mysql:host=localhost;dbname=test", "root", "root");
$received_data = json_decode(file_get_contents("php://input"));
$data = array();

######################################################
###################################################### fetchall
######################################################
if($received_data->action == 'fetchall') {
    $query = "SELECT * FROM tbl_sample ORDER BY id DESC ";
    $DB = $connect->prepare($query);
    $DB->execute();
    while($row = $DB->fetch(PDO::FETCH_ASSOC)) {
      $data[] = $row;
    }
    echo json_encode($data);
}

######################################################
###################################################### insert
######################################################
if($received_data->action == 'insert') {
    $data = array(
      ':first_name' => $received_data->firstName,
      ':last_name'  => $received_data->lastName
    );
    $query = "INSERT INTO tbl_sample (first_name, last_name) VALUES (:first_name, :last_name) ";
    $DB = $connect->prepare($query);
    $DB->execute($data);
    $output = array('message' => 'Kişi eklendi');
    echo json_encode($output);
}

######################################################
###################################################### fetchSingle
######################################################
if($received_data->action == 'fetchSingle') {
    $query = "SELECT * FROM tbl_sample WHERE id = '{$received_data->id}'";
    $DB = $connect->prepare($query);
    $DB->execute();
    $result = $DB->fetchAll();
    foreach($result as $row) {
      $data['id'] = $row['id'];
      $data['first_name'] = $row['first_name'];
      $data['last_name']  = $row['last_name'];
    }
    echo json_encode($data);
}

######################################################
###################################################### update
######################################################
if($received_data->action == 'update') {
    $data = array(
      ':first_name' => $received_data->firstName,
      ':last_name'  => $received_data->lastName,
      ':id'         => $received_data->hiddenId
    );
    $query = "UPDATE tbl_sample SET first_name = :first_name, last_name = :last_name WHERE id = :id ";
    $DB = $connect->prepare($query);
    $DB->execute($data);
    $output = array('message' => 'Kişi bilgileri güncellendi');
    echo json_encode($output);
}

######################################################
###################################################### delete
######################################################
if($received_data->action == 'delete') {
    $query = "DELETE FROM tbl_sample WHERE id = '{$received_data->id}'";
    $DB = $connect->prepare($query);
    $DB->execute();
    $output = array('message' => 'Kişi bilgileri silindi');
    echo json_encode($output);
}

?>
