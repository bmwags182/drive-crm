<?php
/*------------------------------
 *  Drive CRM - Ticket Class
 *  Author: Bret Wagner
 *------------------------------
 */

class Ticket {
    public $id;
    public $clientid;           // input required select id, name from clients order by name asc
    public $assigned;           // input for admins
    public $status;             // input visible for admin, hidden for leads
    public $request_type;       // input required
    public $date;               // input hidden current date
    public $due_date;           // input required minimum curdate+2 weeks
    public $priority;           // input visible for admins
    public $edit_date;          // input hidden
    public $edit_by;            // input hidden
    public $message;            // input required
    public $usesrid;            // input hidden, current user
    public $pod;                // input visible to admin, default to $user->pod when lead



    public function __construct($id = null) {
        $this->id = $id;
        if (!is_null($this->id) && $this->id != 0) {
            $this->read();
        } else {
            die("No Ticket selected");
        }
    }


    
    public function create_ticket($cientid, $userid, $due_date, $message, $pod, $request_type) {
        $db = new Database();
        $user = new User($_SESSION['userid']);
        if (!$user->level <=2) {
            die("You cnnot create tickets.");
        }
        $query = "INSERT INTO tickets (clientid, userid, date, due_date, message, pod, request_type) VALUES :clientid, :userid, :date, :due_date, :message, :pod, :request_type";

        $db->query($query);
        $db->bind(':clientid', $clientid);
        $db->bind(':userid', $userid);
        $db->bind(':date', date(m,d,Y));
        $db->bind(':due_date', $due_date);
        $db->bind(':message', $message);
        $db->bind(':request_type', $request_type);
        $db->bind(':pod', $pod);

        $db->execute();
        $this->id = $db->last_insert_id();
        $this->read();
    }



    public function read() {
        $db = new Database();
        $query = "SELECT * FROM tickets WHERE id = :id";
        $db->query($query);
        $db->bind(':id', $this->id);
        $row = $db->single();
        $this->clientid = $row['clientid'];
        $this->userid = $row['userid'];
        $this->due_date = $row['due_date'];
        $this->message = $row['message'];
        $this->pod = $row['pod'];
        $this->status = $row['status'];
        $this->assigned = $row['assigned'];
        $this->date = $row['date'];
        $this->edit_date = ['edit_date'];
        $this->edit_by = $row['edit_by'];
        $this->priority = $row['priority'];
        $this->id = $row['id'];
        return $row;
    }



    public function update_ticket($data) {
        $db = new Database();
        $query = "UPDATE tickets SET ";

        if (!$this->user_can_edit($_SESSION['userid'])) {
            die("You are not allowed to edit tickets.");
        }

        if ($data['priority'] && $data['priority'] != '') {
            $query_array[] = "priority = :priority";
            $db->bind(':priority', $data['priority']);
        }

        if ($data['clientid'] && $data['clientid'] != '') {
            $query_array[] = "clientid = :clientid";
            $db->bind(':clientid', $data['clientid']);
        }

        if ($data['due_date'] && $data['due_date'] != '') {
            $query_array[] = "due_date = :due_date";
            $d->bind(':due_date', $data['due_date']);
        }

        if ($data['message'] && $data['message'] != '') {
            $query_array[] = "message = :message";
            $db->bind(':message', $data['due_date']);
        }

        if ($data['assigned'] && $data['assigned'] != '') {
            $query_array[] = "assigned = :assigned";
            $db->bind(':assigned', $data['assigned']);
        }

        if ($data['edit_date'] && $data['edit_date'] != '') {
            $query_array[] = "edit_date = :edit_date";
            $db->bind(':edit_date', $data['edit_date']);
        }

        if ($data['edit_by'] && $data['edit_by'] != '') {
            $query_array[] = "edit_by = :edit_by";
            $db->bind(':edit_by', $data['edit_by']);
        } 

        $query_array = implode(", ", $query_array);
        $query = $query . $query_array . " WHERE id = :id";

        $db->bind(':id', $this->id);
        $db->query($query);
        $db->execute();
        $this->read();
    }



    public function delete() {
        $db = new Database();
        if (!$this->user_can_delete($_SESSION['userid'])) {
            die("You are not allowed to delete tickets.");
        }

        $query = "DELETE FROM tickets WHERE id = :id";
        $db->query($query);
        $db->bind(':id', $_SESSION['userid']);
        $db->execute();
        if (!$this->read) {
            return true;
        } else {
            return false;
        }
    }



    public function can_user_view($userid) {
        $user = new User($userid);
        if ($user->is_admin() || $user->pod == $this->pod) {
            return true;
        } else {
            return false;
        }
    }



    public function can_user_edit($userid) {
        $user = new User($userid);
        if ($user->is_admin() || ($user->pod == $this->pod && $user->level >= 3)) {
            return true;
        } else {
            return false;
        }
    }



    public function can_create($userid) {
        $user = new User($userid);
        if ($user->level >= 3) {
            return true;
        } else {
            return false;
        }
    }



    public function get_notes($limit = null) {
        $db = new Database();
        $query = "SELECT * FROM ticket_notes WHERE ticketid = :ticketid";
        if ($limit && $limit != '') {
            $query = $query . " LIMIT :limit";
            $db->query($query);
            $db->bind(':limit', $limit);
        } else {
            $db->query($query);
        }
        $db->bind(':ticketid', $this->id);
        return $db->result_set();
    }
}