<?php
/*------------------------------
 *  Drive CRM - Account Class
 *  Author: Bret Wagner
 *------------------------------
 */

class Account {
    private $id;
    private $clientid;
    private $name;
    private $url;
    private $username;
    private $password;
    private $pod;


    /**
     * create new instance of account
     * @param int $clientid [description]
     * @param [type] $id       [description]
     */
    public function __construct($id = null) {
        $this->id = $id;
        if(!is_null($this->id) && $this->id > 0) {
            $this->read();
        } else {
            // No account selected
            die("You didn't select an account");
        }
    }


    /**
     * create new account in the database
     * @param  int    $clientid  id for the client on the account
     * @param  string $name      name for the online account
     * @param  string $url       link to the online account
     * @param  string $username  username for the online account
     * @param  string $password  password for the online account
     * @param  int    $pod       pod number for the parent client
     * @return array  returns the account information
     */
    public function create($clientid, $name, $url, $username, $password, $pod) {
        $db = new Database();
        $query = "INSERT INTO accounts (name, clientid, url, username, password) VALUES (:name, :clientid, :url, :username, :password)";
        $db->query($query);
        $db->bind(':name', $name);
        $db->bind(':clientid', $clientid);
        $db->bind(':url', $url);
        $db->bind(':username', $username);
        $db->bind(':password', $password);
        $db->bind(':pod', $pod);

        $db->execute();

        $this->id = $db->insert_last_id();
        return $this->read();        
    }


    /**
     * get information on the account
     * @return array  information for the account
     */
    public function read() {
        $db = new Database();
        $query = "SELECT * FROM accounts WHERE id = :id";
        $db->query($query);
        $db->bind(':id', $this->id);
        $row = $db->single();
        $this->clientid = $row['clientid'];
        $this->name = $row['name'];
        $this->url = $row['url'];
        $this->username = $row['username'];
        $this->password = $row['password'];
        return $row;
    }


    /**
     * update account information in the database
     * @param  array  $data  new information from update form
     * @return array  array with new information in the database 
     */
    public function update($data) {
        $userid = $_SESSION['userid'];
        $db = new Database();
        if (!$this->user_can_edit) {
            die("You are not allowed to edit this client");
        }
        $client = new Client($data['clientid']);
        $pod = $client->pod;

        $query =  "UPDATE accounts SET name = :name, clientid = :clientid, url = :url, usesrname = :username, password = :password, pod = :pod";
        $password = encrypt_string($data['password']);
        $db->query($query);
        $db->bind(':name', $data['name']);
        $db->bind(':clientid', $data['clientid']);
        $db->bind(':url', $data['url']);
        $db->bind(':username', $data['username']);
        $db->bind(':password', $password);
        $db->bind(':pod', $pod);

        $db->execute();
        return $this->read();
    }


    /**
     * delete the account from the database
     * @return boolean  returns true when account deleted
     */
    public function delete() {
        $db = new Database();
        if (!$this->user_can_delete($_SESSION['userid'])) {
            die("You are not allowed to delete this account.");
        }

        $query = "DELETE FROM accounts WHERE id = :id";
        $db->query($query);
        $db->bind(':id', $this->id);
        if(!$this->read()) {
            return true;
        }    
        return false;
    }


    /**
     * determine user access level
     * @param  int      $userid id of the current user
     * @return boolean  returns true if user has the correct access level
     */
    public function user_can_view($userid) {
        $user = new User($userid);
        if ($user->is_admin() || $user->pod == $this->pod) {
            return true;
        } else {
            return false;
        }
    }

    
    /**
     * determine user access level
     * @param  int      $userid id of the current user
     * @return boolean  returns true if user has the correct access level
     */
    public function user_can_edit($userid) {
        $user = new User($userid);
        if ($user->is_admin() || ($user->pod == $this->pod && $user->level >= 2)) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * determine user access level
     * @param  int      $userid id of the current user
     * @return boolean  returns true if user has the correct access level
     */
    public function user_can_delete($userid) {
        $user = new User($userid);
        if ($user->is_admin() || ($user->pod == $this->pod && $user->level >= 3)) {
            return true; 
        } else {
            return false;
        }

    }


    /**
     * view decrypted password
     * @return string  deccrypted password for the account
     */
    public function view_password() {
        if ($this->user_can_view) {
            return decrypt_string($this->password);
        } else {
            $_SESSION['error'] = "You are not allowed to view this password";
        }
    }
}
