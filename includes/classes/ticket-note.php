<?php
/*------------------------------
 *  Drive CRM - Ticket Note Class
 *  Author: Bret Wagner
 *------------------------------
 */

class Ticket_note {
    public $id;
    public $ticketid;
    public $userid;
    public $date;
    public $message;
    public $edit_date;
    public $edit_by;
    public $pod;


    /**
     * create a new instance of a ticket_note
     * @param int $id  id for the note
     */
    public function __construct($id = null) {
        $this->id = $id;
        if (!is_null($this->id)) {
            $this->read();
        }
    }


    /**
     * create a new ticket note in the database
     * @param  int    $ticketid  id for the note on the ticket
     * @param  int    $userid    id for the user which created the note
     * @param  string $message   message body for the note
     * @param  int    $pod       pod number that can see the note
     * @return none              calls function to read row
     */
    public function create($ticketid, $userid, $message, $pod) {
        $db = new Database();
        $query = "INSERT INTO ticket_notes (ticketid, userid, date, message, pod) VALUES :ticketid, :userid, :date, :message, :pod";
        $db->query($query);
        $db->bind(':ticketid', $ticketid);
        $db->bind(':userid', $userid);
        $db->bind(':date', date(m,d,Y));
        $db->bind(':message', $message);
        $db->bind(':pod', $pod);
        $db->execute();

        $this->id = $db->last_insert_id();
        $this->read();
    }


    /**
     * read note info from the database
     * @return array  note information from the database
     */
    public function read() {
        $db = new Database();
        $query = "SELECT * FROM ticket_notes WHERE id = :id";
        $db->query($query);
        $row = $db->single();
        $this->id = $row['id'];
        $this->ticketid = $row['ticketid'];
        $this->userid = $row['userid'];
        $this->date = $row['date'];
        $this->message = $row['message'];
        $this->edit_date = $row['edit_date'];
        $this->edit_by = $row['edit_by'];
        $this->pod = $row['pod'];

        return $row;
    }


    /**
     * update note information in the database
     * @param  array $data  new note information from update form
     * @return none         calls function to read the new information
     */
    public function update($data) {
        $db = new Database();
        $query = "UPDATE ticket_notes SET ";

        if ($data['message'] && $data['message'] != '') {
            $query_array[] = "message = :message";
            $db->bind(':message', $data['message']);
        }

        if ($data['edit_date'] && $data['edit_date'] != '') {
            $query_array[] = "edit_date = :edit_date";
            $db->bind(':edit_date', $data['edit_date']);
        }

        if ($data['edit_by'] && $data['edit_by']) {
            $query_array[] = "edit_by = :edit_by";
            $db->bind(':edit_by', $data['edit_by']);
        }

        $query = $query . implode(", ", $query_array) . " WHERE id = :id";
        $db->bind(':id', $this->id);
        $db->query($query);
        $db->execute();
        $this->read();
    }


    /**
     * delete note from the databse
     * @return boolean  returns true when the record has been deleted
     */
    public function delete() {
        $db = new Databse();
        if (!$this->user_can_delete($_SESSION['userid'])) {
            die("You cannot delete notes");
        }

        $query = "DELETE FROM ticket_notes WHERE id = :id";
        $db->query($query);
        $db->bind(':id', $_SESSION['userid']);
        $db->execute();
        if (!$this->read()) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * determines user access level
     * @param  int     $userid  id of the current user
     * @return boolean          returns true if the user can view the note
     */
    public function can_user_view($userid) {
        $user = new User($userid);
        if (!$user->is_admin() || $user->pod != $this->pod) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * determine user access level
     * @param  int     $userid  id of the current user
     * @return boolean          returns true if the user can edit a note
     */
    public function can_user_edit($userid) {
        $user = new User($userid);
        $creator = new User($this->userid);
        if ($user->id == $this->userid || $user->level > $creator->level) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * determine user access level
     * @param  int      $userid  id of the current user
     * @return boolean           returns true if the user has delete access
     */
    public function can_user_delete($userid) {
        $user = new User($userid);
        if (!$user->is_admin()) {
            return false;
        } else {
            return true;
        }
    }
}
