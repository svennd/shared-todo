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
$tid 		= (isset($_POST['id'])) ? trim($_POST['id'], 'todo_id_') : false;
$value		= (isset($_POST['id'])) ? htmlspecialchars($_POST['value']) : false;

if ($tid && $value)
{
	$core->db->sql('UPDATE `todo_list` SET `content` =  "' . $value . '" WHERE  `id` ="' . (int) $tid . '" && `user` = "' . $user_id . '" limit 1;', __FILE__, __LINE__);
}

print_r($_POST['value']);

# show output to screen
$core->close();
