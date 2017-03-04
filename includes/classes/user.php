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
    private $office;

    /**
     * create new instance of the user
     * @param integer $id user id
     */
    public function __construct($id = null) {
        $this->id = $id;
        if (!is_null($this->id) && $this->id > 0) {
            $this->get_data();
        }
    }


    /**
     * create a new user in the database
     * @param  int      $level      access level of the user
     * @param  string   $title      user's position at the company
     * @param  string   $first_name user's first name
     * @param  string   $last_name  user's last name
     * @param  string   $email      user's email address
     * @param  string   $username   username for user
     * @param  string   $password   user's password
     * @param  int      $pod        which pod the user belongs to
     * @param  int      $office     employee's home office
     */
    public function create($level, $title, $office, $pod, $first_name, $last_name, $email, $username, $password) {
        $password = encrypt_string($password);
        $db = new Databse();
        $query = "INSERT INTO users (level, title, fname, lname, email, username, password, pod, office) VALUES (:level, :title, :fname, :lname, :email, :username, :password, :pod, :office)";
        $db->query($query);

        $db->bind(':level', $level);
        $db->bind(':fname', $first_name);
        $db->bind(':lname', $last_name);
        $db->bind(':title', $title);
        $db->bind(':username', $username);
        $db->bind(':password', $password);
        $db->bind(':pod', $pod);
        $db->bind(':office', $office);

        $db->execute();

        $this->id = $db->last_insert_id();
        $this->read();
    }


    /**
     * get user information
     * @return array  information for the user
     */
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
        $this->office = $row['office'];
        return $row;
    }


    /**
     * update user information in the database
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function update($data) {
        $old_data = $this->read();
        if (!$this->can_edit($id)) {
            die("You can't edit that user.");
        }
        $db = new Database();
        $password = encrypt_string($data['password']);
        $query = "UPDATE user SET level = :level, fname = :fname, lname = :lname, email = :email, password = :password, pod = :pod, office = :office WHERE id = :id";
        $db->query($query);

        $db->bind(':id', $this->id);
        $db->bind(':level', $data['level']);
        $db->bind(':title', $data['title']);
        $db->bind(':fname', $data['fname']);
        $db->bind(':lname', $data['lname']);
        $db->bind(':email', $data['email']);
        $db->bind(':password', $password);
        $db->bind(':office', $data['office']);
        $db->bind(':pod', $data['pod']);

        if (!check_password($old_data['password'], $password)) {
            $_SESSION['error'] = 'Password does not match what is on file.';
        }

        $db->execute();
        $this->read();
    }

    /**
     * get client list for user
     * @return array  list of clients user has access to
     */
    public function get_clients() {
        $db = new Databse();
        $user = new User($_SESSION['userid']);
        if ($user->is_admin()) {
            $query = "SELECT * FROM clients";
            $db->query($query);
        } else {
            $query = "SELECT * FROM clients WHERE pod = :pod AND office = :office";
            $db->query($query);
            $db->bind(':pod', $this->pod);
            $db->bind(':office', $this->office);
        }
        
        $rows = $db->result_set();
        return $rows;
    }


    /**
     * delete user from database
     * @return boolean  returns true if user is deleted
     */
    public function delete() {
        $userid = $_SESSION['userid'];

        if (!$this->user_can_delete($userid) {
            die("You can't kill this person.");
        }
        $db = new Database();
        $query = "DELETE FROM users WHERE id = :id";
        $db->query($query);
        $db->bind(':id', $this->id);
        $db->execute();
        if (!$this->read()) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * login as authorized user
     * @param  string  $username  username or email address
     * @param  string  $password  passowrd ofr the user
     */
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


    /**
     * determine if user is logged in
     * @return boolean  returns true if user is logged in as an authorized user
     */
    public function is_authorized() {
        if ($_SESSION['authorized'] && $_SESSION['authorized'] == true) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * determine user access level
     * @return boolean  returns true if the user is an admin
     */
    public function is_admin() {
        $data = $this->read();
        if ($data['level'] >= 4) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * determine user access level
     * @param  int      $userid  id of the current user
     * @return boolean  returns true if current user can edit $this
     */
    public function can_edit($userid) {
        $user = new User($userid);
        $user_data = $user->read();
        if ($user->id == $this->id || $user->level >= 3) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * determine user acccess level
     * @param  int      $userid  id of the current user
     * @return boolean  returns true if the  user can delete $this
     */
    public function user_can_delete($userid) {
        $user = new User($userid);
        if ($user->level >= 3 && $user->level > $this->level) {
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