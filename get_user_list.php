<?php
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

// $arr = array("options"=> array('option','option1'));

// echo '{"options": ["Option 1", "Option 2","Option 3","Option 4","Option 5"]}';
// echo '["Option 1", "Option 2","Option 3","Option 4","Option 5"]';

#   Svenn D'Hert
include('_main/main_frame.php');
 
# initialise frame
$core = new core();

# load modules
$core->load_modules(array('database', 'sessions', 'users'));

# not logged
if (!$core->user->is_logged()) {header("Location: ucp.php");}

$partial = htmlspecialchars($_POST['query']);
$core->db->sql('select username from user_data where username LIKE "%'. $partial .'%" limit 0,10;', __FILE__, __LINE__);

foreach ($core->db->result as $user) {
	$users[] = $user['username'];
}

echo json_encode($users);
# show output to screen
$core->close();
?>