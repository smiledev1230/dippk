<?php
class Message {
	public function __construct() {
		
    }
    public function get_users(){
        
        $user_id = $_SESSION['usr_id'];

        $sql = "SELECT * FROM usr_profile WHERE ID !='$user_id' ORDER BY First_Name ASC, Last_Name ASC";
        $result = mysql_query($sql);
        $users = array();
        while($row = mysql_fetch_assoc($result)){
            $users[] = $row;
        }
        return $users;
    }
    /* public function get_users_ordered_by_latest_messages_first(){
        
        $user_id = $_SESSION['usr_id'];
        $sql = "SELECT * FROM (SELECT up.*, um.id as message_id,um.message,um.datetime FROM usr_profile up LEFT JOIN usr_messages um ON up.ID=um.sender_id WHERE up.ID !='$user_id' ORDER BY um.datetime DESC, up.First_Name ASC, up.Last_Name ASC) AS t GROUP BY t.ID";
        $result = mysql_query($sql);
        echo mysql_error();
        $users = array();
        while($row = mysql_fetch_assoc($result)){
            $users[] = $row;
        }
        return $users;
    } */

    public function get_messages($id){
        $current_user_id = $_SESSION['usr_id'];
        $sql = "SELECT um.*,up.Image_Ext FROM usr_messages um INNER JOIN usr_profile up ON um.sender_id=up.ID  WHERE (um.sender_id='$id' AND um.receiver_id='$current_user_id') || (um.sender_id='$current_user_id' AND um.receiver_id='$id') ORDER BY datetime DESC";
        $result = mysql_query($sql);
        $messages = array();
        while($row = mysql_fetch_assoc($result)){
            $messages[] = $row;
        }
        return $messages;
    }

    public function get_user($id){
        $sql = "SELECT * FROM usr_profile WHERE ID ='$id'";
        $result = mysql_query($sql);
        $row = mysql_fetch_assoc($result);
        return $row;       
    }

    public function send_message(){
        $sender_id = $_SESSION['usr_id'];
        $receiver_id = $_POST['receiver_id'];
        $message = $_POST['message'];
        $current_time = date('Y-m-d H:i:s');
        if(!empty($message)){
            $sql = "INSERT INTO usr_messages(sender_id,receiver_id,message,datetime)VALUES('$sender_id','$receiver_id','$message','$current_time')";
            mysql_query($sql);
        }
        
    }

    public function get_unread_messages($user_id){
        $sql = "SELECT * FROM (SELECT up.*, um.id as message_id,um.message,um.datetime FROM usr_messages um INNER JOIN usr_profile up ON um.sender_id=up.ID WHERE um.receiver_id='$user_id' AND um.seen='0' ORDER BY datetime DESC) AS t GROUP BY t.ID ";
        $result = mysql_query($sql);
        $messages = array();
        while ($row = mysql_fetch_assoc($result)) {
            $messages[] = $row;
        }
        return $messages;
    }

    public function format_message_date($time){
        $current_time = date('Y-m-d H:i:s');
        $previous_day = date('Y-m-d H:i:s',strtotime($current_time." -1 days"));

        if($time<=$previous_day){
            return date('M d',strtotime($time));
        }
        else{
            return date('h:i a',strtotime($time));
        }
        
    }

    public function truncate_message($message,$letters=25){
        if(strlen($message)<=$letters){
            return $message;
        }
        else{
            return trim(substr($message,0,$letters))."...";
        }
    }

    public function mark_messages_read($sender_id,$receiver_id){
        $sql = "UPDATE usr_messages SET seen=1 WHERE sender_id='$sender_id' AND receiver_id='$receiver_id' ";
        mysql_query($sql);
    }
}