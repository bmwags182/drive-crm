<?php
/*------------------------------
 *  Drive CRM - User Class
 *  Author: Bret Wagner
 *------------------------------
 */


class User {
    private $id;
    private $level;
    private $title;
    private $first_name;
    private $last_name;
    private $username;
    private $password;
    private $email;
    private $pod;

    /**
     * create the user
     * @param integer $id user id
     */
    public function __construct($id = null) {
        $this->id = $id;
        if(!is_null($this->id) && $this->id > 0) {
            $this->get_data();
        }
    }


    public function create($level, $title, $first_name, $last_name, $email, $username, $password, $pod) {
        $password = encrypt_string($password);
        $db = new Databse();
        $query = "INSERT INTO users (level, title, fname, lname, email, username, password, pod) VALUES (:level, :title, :fname, :lname, :email, :username, :password, :pod)";
        $db->query($query);

        $db->bind(':level', $level);
        $db->bind(':fname', $first_name);
        $db->bind(':lname', $last_name);
        $db->bind(':title', $title);
        $db->bind(':username', $username);
        $db->bind(':password', $password);
        $db->bind(':pod', $pod);

        $db->execute();

        $this->id = $db->last_insert_id();
        $this->read();
    }


    public function read() {
        $db = new Database();
        $query = "SELECT * FROM users WHERE id = :id";
        $db->query($query);
        $db->bind(':id', $this->id);
        $row = $db->single();
        $this->id = $row['id'];
        $this->level = $row['level'],
        $this->first_name = $row['fname'];
        $this->last_name = $row['lname'];
        $this->email = $row['email'];
        $this->username = $row['username'];
        $this->pod = $row['pod'];
        return $row;
    }


    public function update($data) {
        $old_data = $this->read();
        if(!$this->can_edit($id)) {
            die("You can't edit that user.");
        }
        $db = new Database();
        $password = encrypt_string($data['password']);
        $query = "UPDATE user SET level = :level, fname = :fname, lname = :lname, email = :email, password = :password, pod = :pod WHERE id = :id";
        $db->query($query);

        $db->bind(':id', $this->id);
        $db->bind(':level', $data['level']);
        $db->bind(':title', $data['title']);
        $db->bind(':fname', $data['fname']);
        $db->bind(':lname', $data['lname']);
        $db->bind(':email', $data['email']);
        $db->bind(':password', $password);
        $db->bind(':pod', $data['pod']);

        if (!check_password($old_data['password'], $password)) {
            $_SESSION['error'] = 'Password does not match what is on file.';
        }

        $db->execute();
        $this->read();
    }


    public function get_clients() {
        $db = new Databse();
        $query = "SELECT * FROM clients WHERE pod = :pod";
        $db->bind(':pod', $this->pod);
        $rows = $db->result_set();
        return $rows;
    }


    public function delete() {
        $userid = $_SESSION['userid'];
        $del_user = new USER();

        if(!$this->can_user_delete($userid, $del_user->read['id'])) {
            die("You can't kill this person.");
        }
        $db = new Database();
        $query = "DELETE FROM users WHERE id = :id";
        $db->query($query);
        $db->bind(':id', $del_user);
        $db->execute();
        if (!$del_user->read()) {
            return true;
        } else {
            return false;
        }
    }


    public function login($username, $password) {
        $password = encrypt_string($password);
        $db = new Database();
        $query = "SELECT * FROM users WHERE username = :username OR email = :username AND password = :password";
        $db->query($query);
        $db->bind(':username', $username);
        $db->bind(':password', $password);
        $rows = $db->result_set();
        if ($db->row_count() == 1) {
            $_SESSION['authorized']= true;
            $_SESSION['userid'] = $rows[0]['id'];
            header('Location: ' . DIRADMIN);
            exit();
        } else {
            $_SESSION['error'] = "Sorry, your username or password was incorrect.";
        }
    }


    public function is_authorized() {
        if($_SESSION['authorized'] && $_SESSION['authorized'] == true) {
            return true;
        } else {
            return false;
        }
    }


    public function is_admin() {
        $data = $this->read();
        if ($data['level'] >= 3) {
            return true;
        } else {
            return false;
        }
    }

    public function can_edit() {
        $user = new User($_SESSION['userid']);
        $user_data = $user->read();
        if ($user->is_admin() || $id == $this->id) {
            return true;
        } else {
            return false;
        }
    }


    public function logout() {
        unset($_SESSION['authorized']);
        unset($_SESSION['userid']);
        header('Location: ' . DIR);
        exit();
    }
}