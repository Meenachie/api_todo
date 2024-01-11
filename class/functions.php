<?php

class user {
    private $db;

    public function __construct(database $db){
        $this->db = $db;
    }

    public function signup(){
        $data = json_decode(file_get_contents('php://input'), true);
        $name = $data['name'];
        $email = $data['email'];
        $password = $data['password'];
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `user`(name, email, password) VALUES(?,?,?)";
        $stmt = mysqli_stmt_init($this->db->conn);
        $preparestmt= mysqli_stmt_prepare($stmt,$sql);
        if($preparestmt){
            mysqli_stmt_bind_param($stmt,"sss",$name,$email,$hash);
            mysqli_stmt_execute($stmt);
            echo json_encode(['message' => 'data inserted successfully']);
        }else{
            echo json_encode(['message' => 'Something went wrong']);
        }
    }
}
$user= new user($db);

class crud {
    private $db;
  
    public function __construct(database $db) {
      $this->db = $db;
    }

    public function create(){
        $data = json_decode(file_get_contents('php://input'), true);
        $email = $data['email'];
        $task = $data['task'];
        $sql="SELECT id FROM `user` where email = '$email'";
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        $id = $row['id'];
        if($row>0){
            $sql = "INSERT INTO `task` (task,user_id) VALUES(?,?)";
            $stmt = mysqli_stmt_init($this->db->conn);
            $preparestmt= mysqli_stmt_prepare($stmt,$sql);
            if($preparestmt){
                mysqli_stmt_bind_param($stmt,"si",$task,$id);
                mysqli_stmt_execute($stmt);
                echo json_encode(['message' => 'task inserted successfully']);
            }else{
                echo json_encode(['message' => 'Something went wrong']);
            }
        }
    }

    public function read(){
        $data = json_decode(file_get_contents('php://input'), true);
        $email = $data['email'];
        if(isset($_GET['id'])){
            $id= $_GET['id'];
            $sql= "SELECT t.id,t.task,t.status,t.created_on  FROM `task` as t LEFT JOIN `user` as u ON t.user_id=u.id WHERE u.email='$email' AND t.id='$id'";
            $result = $this->db->query($sql);
            while($row = mysqli_fetch_array($result)){
            $resp= array( "task"=> $row['task'],
                            "status"=> $row['status'],
                            "date"=> $row['created_on']);
            }
            echo json_encode($resp);
        }else{
        $sql= "SELECT t.id,t.task,t.status,t.created_on  FROM `task` as t LEFT JOIN `user` as u ON t.user_id=u.id WHERE u.email='$email'";
        $result = $this->db->query($sql);
        $idd = 1;
        while($row = mysqli_fetch_array($result)){
          $resp= array("id"=> $idd++,
                            "task"=> $row['task'],
                            "status"=> $row['status'],
                            "date"=> $row['created_on']);
        }
        echo json_encode($resp);
        }
    }

    public function update(){
        $id=$_GET['id'];
        $data = json_decode(file_get_contents('php://input'), true);
        
        if(isset($data['task']) && isset($data['status'])){
            $task = $data['task'];
            $status = $data['status'];
            $sqltask = "UPDATE `task` SET task='$task',status='$status',created_on= now() WHERE id='$id'";
            $resulttask= mysqli_query($this->db->conn,$sqltask);
            if($resulttask){
                echo json_encode(['message' => 'updated successfully']);
            }else {
            echo "something went wrong";
            }
        }
        else if(isset($data['task'])){
            $task = $data['task'];
            $sqltask = "UPDATE `task` SET task='$task',created_on= now() WHERE id='$id'";
            $resulttask= mysqli_query($this->db->conn,$sqltask);
            if($resulttask){
                echo json_encode(['message' => 'updated successfully']);
            }else {
            echo "something went wrong";
            }
        }
        else if(isset($data['status'])){
            $status = $data['status'];
            $sqlstatus = "UPDATE `task` SET created_on= now(), status='$status' WHERE id='$id'";
            $resultstatus= mysqli_query($this->db->conn,$sqlstatus);
            if($resultstatus){
                echo json_encode(['message' => 'updated successfully']);
            }else{
            echo "something went wrong";
            }
        }
    }
    
    public function delete(){
        $id = $_GET['id'];
        $sql = "DELETE FROM `task` WHERE id='$id'";
        $result = $this->db->query($sql);
        if($result){
            echo json_encode(['message' => 'deleted successfully']);
        }else{
            echo json_encode(['message' => 'Something went wrong']);
        }
    }
    
}
$todo = new crud($db);

?>