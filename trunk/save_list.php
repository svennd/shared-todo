<?php
#   Svenn D'Hert
include('_main/main_frame.php');
 
# initialise frame
$core = new core();

# load modules
$core->load_modules(array('database', 'sessions', 'users'));

# not logged
if (!$core->user->is_logged()) {header("Location: ucp.php");}

$user_id 	= $core->user->get_user_id();
$pid 		= $_POST['pid'];
$order		= $_POST['neworder'];
$head 		= array_shift($order);
$sort		= implode($order, ',');
$core->db->sql('INSERT INTO user_sort VALUES ("' . $user_id . '", "' . $pid . '", "' . $sort . '") ON DUPLICATE KEY UPDATE user_sort = "'. $sort .'";', __FILE__, __LINE__);

# show output to screen
$core->close();
?>
