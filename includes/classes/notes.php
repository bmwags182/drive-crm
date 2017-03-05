<?php
/*------------------------------
 *  Drive CRM - User Class
 *  Author: Bret Wagner
 *------------------------------
 */

class Note {
    public $id;
    public $clientid;
    public $userid;
    public $date;
    public $message;
    public $pod;
    public $edit_date;


    /**
     * create  new instance of a note
     * @param int  $id  id for the note
     */
    public function __construct($id = null) {
        $this->id = $id;
        if (!is_null($this->id)) {
            $this->read();
        } else {
            die("You didn't select a note");
        }
    }


    /**
     * create a new note in the database
     * @param  int    $clientid  ID for the client
     * @param  int    $userid    ID for current user
     * @param  date   $date      creation date for the note
     * @param  string $message   messge body of the note
     * @param  int $pod          which pod can view the notes
     * @return none              funtions calls read() to get row
     */
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


    /**
     * read note information
     * @return array  all information on the note
     */
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
    

    /**
     * uodate note in the database
     * @param  array $data  updated informationg from form
     * @return array        note with updated info
     */
    public function update($data) {
        $db = new Database();
        $query = "UPDATE notes SET message = :message, edit_date = :edit_date";
        $db->query($query);
        $db->bind(':message', $data['message']);
        $db->bind(':edit_date', $data['edit_date']);
        $db->execute();

        $this->read();
    }


    /**
     * delete note from database
     * @return boolean  true when note is deleted
     */
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


    /**
     * determine user access level
     * @param  int      $userid  current user id
     * @return boolean  returns true if user can edit
     */
    public function user_can_edit($userid) {
        $user = new User($_SESSION['userid'])
        if ($user->is_admin() || $user->id == $this->userid) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * determine user access level
     * @param  int $userid  current user id
     * @return boolean      returns true when user can delete
     */
    public function user_can_delete($userid) {
        $user = new User($_SESSION['userid']);
        if ($user->is_admin() || ($user->level >= 3 && $user->pod == $this->pod)) {
            return true;
        } else {
            return false;
        }
    }
}