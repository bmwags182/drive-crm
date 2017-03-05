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



    public function __construct($id = null) {
        $this->id = $id;
        if (!is_null($this->id)) {
            $this->read();
        }
    }



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



    public function update($data) {}



    public function delete() {}



    public function can_user_view($userid) {}



    public function can_user_edit($userid) {}



    public function can_user_delete($userid) {}
}