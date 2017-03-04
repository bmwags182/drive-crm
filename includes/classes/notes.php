<?php
/*------------------------------
 *  Drive CRM - User Class
 *  Author: Bret Wagner
 *------------------------------
 */

class Note {
    private $id;
    private $clientid;
    private $userid;
    private $date;
    private $message;
    private $pod;
    private $edit_date;

    public function __construct($id) {
        $this->id = $id;
        if (!is_null($this->id)) {
            $this->read();
        } else {
            die("You didn't select a note");
        }
    }


    public function create($clientid, $userid, $date, $message, $pod) {
        $db = new Database();
        $query = "INSERT INTO notes (clientid, userid, date, message, pod) VALUES :clientid, :userid, :date, message, :pod";
        $db->query($query);

        $db->bind(':clientid', $clientid);
        $db->bind(':userid', $userid);
        $db->bind(':date', $date);
        $db->bind(':message', $message);
        $db->bind(':pod', $pod);

        $db->execute();

        $this->id = $db->insert_last_id();
        $this->read();
    }


    public function read() {
        $db = new Database();
        $query = "SELECT * FROM notes WHERE id = :id";
        $db->query($query);
        $db->bind(':id', $this->id);
        $row = $db->single();
        $this->clientid = $row['clientid'];
        $this->userid = $row['userid'];
        $this->date = $row['date'];
        $this->message = $row['message'];
        $this->pod = $row['pod'];
        return $row;
    }
    

    public function update($data) {
        $db = new Database();
        $query = "UPDATE notes SET message = :message, edit_date = :edit_date";
        $db->query($query);
        $db->bind(':message', $data['message']);
        $db->bind(':edit_date', $data['edit_date']);
        $db->execute();

        $this->read();
    }


    public function delete() {
        $userid = $_SESSSION['userid'];
        $db = new Database();
        if (!$this->user_can_delete($userid)) {
            die("You cannot edit this note");
        }

        $query = "DELETE FROM notes WHERE id = :id";
        $db->query($query);
        $db->bind(':id', $this->id);
        $db->execute();
        if (!$this->read()) {
            return true;
        } else {
            return false;
        }
    }


    public function user_can_edit($userid) {
        $user = new User($_SESSION['userid'])
        if ($user->is_admin() || $user->id == $this->userid) {
            return true;
        } else {
            return false;
        }
    }


    public function user_can_delete($userid) {
        $user = new User($_SESSION['userid']);
        if ($user->is_admin() || ($user->level >= 3 && $user->pod == $this->pod)) {
            return true;
        } else {
            return false;
        }
    }
}