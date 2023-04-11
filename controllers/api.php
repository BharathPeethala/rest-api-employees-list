<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, PUT');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

include_once '../config/config.php';
$method = $_SERVER['REQUEST_METHOD'];
function printSqlRows($result)
{
    $data = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($data, [
                "id" => $row['id'],
                "name" => $row['name'],
                "state" => $row['state'],
                "age" => $row['age'],
            ]);
        }
    }
    return $data;
}
if ($method === 'GET') {

    // queries
    $query = 'SELECT * FROM EMPLOYEES';
    //
    $result = mysqli_query($conn, $query);
    // http_response_code(200);
    echo json_encode(printSqlRows($result));
} elseif ($method === 'POST') {

    // queries
    $query = $conn->prepare("INSERT INTO employees (name, age, state) VALUES(?,?,?)");
    $query->bind_param("sii", $_POST['name'], $_POST['age'], $_POST['state']);
    //
    if ($query->execute() === TRUE) {
        echo 'Data inserted Successfully';
        http_response_code(200);
    }
} elseif ($method === "DELETE") {
    $id = $_GET['id'];
    // queries
    $query = "SELECT * FROM EMPLOYEES WHERE ID = $id";
    $result = mysqli_query($conn, $query);
    $query = "DELETE FROM EMPLOYEES WHERE ID = $id";
    //
    mysqli_query($conn, $query);
    http_response_code(200);
    echo json_encode(printSqlRows($result));
} elseif ($method == 'PUT') {
    // queries
    $_PUT = json_decode((file_get_contents("php://input")));
    $query = $conn->prepare("UPDATE employees SET name = ?,state =?,age=? WHERE id =?");
    $query->bind_param("siii", $_PUT->name, $_PUT->state, $_PUT->age, $_PUT->id);
    if ($query->execute() === TRUE) {
        $id = $_PUT->id;
        $query = "SELECT * FROM EMPLOYEES WHERE ID = '$id'";
        $result = mysqli_query($conn, $query);
        http_response_code(200);
        echo json_encode(printSqlRows($result));
    }
} else {
    echo 'Status Code 400: Bad Reqeuest. GET, POST, PUT and DELETE methods are allowed';
}

$conn->close();
