<?
    class User {
        function connect_db() {
            $mysqli_function = new mysqli(
                "localhost",
                "root",
                "",
                "table_of_users"
                );
            if ($mysqli_function->connect_error) {
                    die('Ошибка подключения (' . $mysqli_function->connect_errno . ') '
                    . $mysqli_function->connect_error);
            }          
            return $mysqli_function;      
        }

        function showUsers() {
            $mysqli = $this->connect_db();
            $result = $mysqli->query("SELECT id,name,second_name,e_mail FROM users");
            $rows = $result->fetch_all(MYSQLI_ASSOC); // create an array of the table "users"
          if(count($rows) > 0) {
                return $rows;       
          }
          $this->connectClose($mysqli);;
        }

        function addNewUser() {
            $mysqli = $this->connect_db();
            if(isset($_POST['add-user'])) {
                if(!empty($_POST['user-name']) && //if the form isn't empty, add new user to the table 
                   !empty($_POST['user-second_name']) &&
                   !empty($_POST['user-mail']) 
                ) {
                    $name = trim(strip_tags($_POST['user-name']));
                    $secondName = trim(strip_tags($_POST['user-second_name']));
                    $mail = trim(strip_tags($_POST['user-mail']));
                    //add new user to the table 
                    $result = $mysqli->query("INSERT INTO users 
                        (name,second_name,e_mail)
                    VALUES 
                        ('$name','$secondName','$mail')
                   ");
                }
            }
            $this->connectClose($mysqli);
        }

        function getUserDataById($id) {
            $mysqli = $this->connect_db();
            $result = $mysqli->query("SELECT id,name,second_name,e_mail FROM users WHERE id=$id");
            return $rows = $result->fetch_assoc();
            $this->connectClose($mysqli);
        }

        function updateUserData($id) {
            $mysqli = $this->connect_db();
            if(isset($_POST['edit-user']) && !empty($_POST['edit-user-name']) 
                                          && !empty($_POST['edit-user-second_name'])
                                          && !empty($_POST['edit-user-mail'])) {
                        //if Edit Form isn't empty, update user           
                        $name=mysqli_real_escape_string($mysqli,$_POST['edit-user-name']);
                        $secondName = mysqli_real_escape_string($mysqli,$_POST['edit-user-second_name']);
                        $mail = mysqli_real_escape_string($mysqli,$_POST['edit-user-mail']);
                        $mysqli -> query("UPDATE 
                                                 users 
                                          SET 
                                                name='$name',
                                                second_name='$secondName',
                                                e_mail='$mail'
                                          WHERE
                                                id=$id
                        ");  
            }   
            $this->connectClose($mysqli);     
        }

        function deleteUser() {
            if(isset($_GET['delete'])) {
                $id = abs((int)$_GET['delete']);
                if(empty($id)) {
                         return false;
                     }
                $mysqli = $this->connect_db();
                $result = $mysqli->query("SELECT id FROM users WHERE id=$id");

                if($result && $result->num_rows == 1) {        
                    $mysqli->query("DELETE FROM users WHERE id=$id");
                }
                $this->connectClose($mysqli);
            }
           
        }

        function connectClose($connect){
            return $connect->close();
        }
    }

    $user = new User();
    if(!empty($_GET['edit']) && is_numeric($_GET['edit'])) {
        $id = $_GET['edit'];
        $dataUser = $user->getUserDataById($id);
    }   
    $user->addNewUser(); 
    $user->updateUserData($id);  
    $user->deleteUser();
?>
