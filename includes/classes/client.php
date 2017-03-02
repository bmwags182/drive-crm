<?php
/*------------------------------
 *  Drive CRM - Functions File
 *  Author: Bret Wagner
 *------------------------------
 */


class Client {
    private $id;
    private $name;
    private $social;
    private $web;
    private $contacts;
    private $pod;


    public function __construct($id = null) {
        $this->id = $id;
        if(!is_null($this->id) && $this->id > 0) {
            $this->read();
        } else {
            $user = new User($_SESSION['userid']);
            return $user->get_clients();
        }
    }


    public function create($id, $name, $pod, $social, $web, $contract = null) {
        $db = new Database();
        $query = "INSERT INTO clients (name, pod, social, web, contracts) VALUES (:name, :pod, :social, :web, :contracts)";
        $db->query($query);
        $db->bind(':name', $name);
        $db->bind(':pod', $pod);
        $db->bind(':social', $social);
        $db->bind(':web', $web);
        $db->bind(':contracts', $contracts);

        $db->execute();

        $this->id = $db->insert_last_id();
        $this->read();
    }


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
        return $row;
    }


    public function update($data) {
        $userid = $_SESSION['userid'];
        $db = new Database();
        if(!$this->can_edit($userid)) {
            die("You can't edit that client.");
        }

        $query = "UPDATE clients SET name = :name, pod = :pod, social = :social, web = :web, contracts = :contracts";
        $db->query($query);
        $db->bind(':name', $data['name']);
        $db->bind(':pod', $data['pod']);
        $db->bind(':social', $data['social']);
        $db->bind(':web', $data['web']);
        $db->bind(':contracts', $data['contracts']);

        $db->execute();
        return $this->read();
    }


    public function delete() {
        $userid = $_SESSION['userid'];
        $db = new Database();
        if(!$this->can_delete($userid)) {
            die("You cannot delete clients.");
        }

        $query = "DELETE FROM clients WHERE id = :id";
        $db->query($query);
        $db->bind(':id', $this->id);
        if(!$this->read()) {
            return true;
        }    
        return false;
    }


    public function can_edit($userid) {
        $user = new User($userid);
        if($user->is_admin() || $user->pod == $this->pod) {
            return true;
        }
        return false;
    }


    public function can_delete($userid) {
        $user = new User($userid);
        if($user->is_admin() || ($user->level >= 2 && $user->pod == $this->pod)) {
            return true;
        }
        return false;
    }
}