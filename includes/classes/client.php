<?php
/*------------------------------
 *  Drive CRM - Functions File
 *  Author: Bret Wagner
 *------------------------------
 */


class Client {
    public $id;
    public $office;
    public $name;
    public $social;
    public $web;
    public $contacts;
    public $pod;
    public $url;


    /**
     * create a new instance of a client
     * @param int $id account id
     */
    public function __construct($id = null) {
        $this->id = $id;
        if (!is_null($this->id) && $this->id > 0) {
            $this->read();
        } else {
            $user = new User($_SESSION['userid']);
            return $user->get_clients();
        }
    }


    /**
     * create a new client in the database
     * @param  int    $id        client id
     * @param  string $name      name for the client
     * @param  int    $pod       pod number for the client
     * @param  string $social    client social contract level
     * @param  string $web       client web contract level
     * @param  array  $contracts links to contract uploads
     * @param  int    $office    determines which office holds the client
     */
    public function create($name, $pod, $social, $web, $office, $contracts) {
        $db = new Database();
        $query = "INSERT INTO clients (name, pod, social, web, contracts) VALUES (:name, :pod, :social, :web, :contracts)";
        $db->query($query);
        $db->bind(':name', $name);
        $db->bind(':pod', $pod);
        $db->bind(':social', $social);
        $db->bind(':web', $web);
        $db->bind(':office', $office);
        $db->bind(':contracts', $contracts);

        $db->execute();

        $this->id = $db->insert_last_id();
        $this->read();
    }


    /**
     * get client information from the database
     * @return array    client information
     */
    public function read() {
        $db = new Databse();
        $query = "SELECT * FROM clients WHERE id = :id";
        $db->query($query);
        $db->bind(':id', $this->id);
        $row = $db->single();
        $this->name = $row['name'];
        $this->pod = $row['pod'];
        $this->social = $row['social'];
        $this->web = $row['web'];
        $this->contracts = $row['contacts'];
        $this->url = $row['url'];
        return $row;
    }


    /**
     * update cilent information in the database
     * @param  array $data  new data from update form
     * @return array        new information
     */
    public function update($data) {
        $userid = $_SESSION['userid'];
        $db = new Database();
        if (!$this->user_can_edit($userid)) {
            die("You can't edit that client.");
        }

        $query = "UPDATE clients SET name = :name, pod = :pod, social = :social, web = :web, office = :office contracts = :contracts";
        $db->query($query);
        $db->bind(':name', $data['name']);
        $db->bind(':pod', $data['pod']);
        $db->bind(':social', $data['social']);
        $db->bind(':web', $data['web']);
        $db->bind(':office', $data['office']);
        $db->bind(':contracts', $data['contracts']);

        $db->execute();
        return $this->read();
    }


    /**
     * delete client from the database
     * @return boolean true when client succesfully deleted
     */
    public function delete() {
        $userid = $_SESSION['userid'];
        $db = new Database();
        if (!$this->user_can_delete($userid)) {
            die("You cannot delete clients.");
        }

        $query = "DELETE FROM clients WHERE id = :id";
        $db->query($query);
        $db->bind(':id', $this->id);
        if (!$this->read()) {
            return true;
        }    
        return false;
    }


    /**
     * get list of the client's online accounts
     * @return array        list of online accounts
     */
    public function get_accounts() {
        $db = new Database();
        $query = "SELECT * FROM accounts WHERE clientid = :clientid";
        $db->query($query);
        $db->bind(':clientid', $this->id);
        return $db->result_set()
    }


    /**
     * count the number of notes on a client
     * @return int  number of rows
     */
    public function count_notes() {
        $db = new Databse();
        $query = "SELECT count(id) AS count FROM notes WHERE clientid = :clientid";
        $db->query($query);
        $db->bind(':clientid', $this->id);
        return $db->single()['count'];
    }


    /**
     * get notes for client
     * @return array  all notes on a client
     */
    public function get_notes() {
        $db = new Databse();
        $query = "SELECT * FROM note WHERE clientid = :clientid";
        $db->query($query);
        $db->bind(':clientid', $this->id);
        return $db->result_set();
    }


    /**
     * determine user access level
     * @param  int      $userid  id of the current user
     * @return boolean  returns true if the user has the proper access level
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
     * @param  int      $userid  id of the current user
     * @return boolean  returns true if the user has the proper access level
     */
    public function user_can_edit($userid) {
        $user = new User($userid);
        if ($user->is_admin() || ($user->pod == $this->pod && $user->level >= 3)) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * determine usesr access level
     * @param  int      $userid  id of the current user
     * @return boolean  returns true if user has the correct access level
     */
    public function user_can_delete($userid) {
        $user = new User($userid);
        if ($user->is_admin() || ($user->level >= 3 && $user->pod == $this->pod)) {
            return true;
        } else {
            return false;
        }
    }



    public function get_tickets($limit = null) {
        $db = new Databse();
        $query = "SELECT * FROM tickets WHERE clientid = :clientid";
        if ($limit && $limit != '') {
            $query = $query . " LIMIT :limit";
            $db->query($query);
            $db->bind(':limit', $limit);
        } else {
            $db->query($query);
        }
        $db->bind(':clientid', $this->id);
        return $db->result_set();
    }



    public function count_tickets() {
        $db = new Databse();
        $query = "SELECT count(id) AS count FROM tickets WHERE clientis = clientid";
        $db->query($query);
        return $db->single()['count'];
    }
}