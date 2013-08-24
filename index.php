<?php
#   Svenn D'Hert
include('_main/main_frame.php');
 
# initialise frame
$core = new core(
					array('title' => 'Mijn Pagina')
				);

# load modules
$core->load_modules(array('view', 'database', 'sessions', 'users', 'todo'));

# not logged
if (!$core->user->is_logged()) {header("Location: ucp.php");}

# head input
$new_project 	= (isset($_GET['add_project'])) ? true : false;
$new_todo 		= (isset($_GET['add_todo'])) 	? true : false;
$project_id 	= (isset($_GET['project_id'])) 	? (int) $_GET['project_id'] : false;
$todo_id 		= (isset($_GET['todo_id'])) 	? (int) $_GET['todo_id'] : false;
$change_topic	= (isset($_GET['change_topic']))? true : false;
$add_user		= (isset($_GET['add_user']))? true : false;

# core settings
$user_id = $core->user->get_user_id();

if ($add_user)
{
	$new_user 	= $_POST['user'];
	$pid_check 	= $_POST['pid'];
	$pid		= $core->session->get('pid');
	
	# is it a user ?
	$new_team_member_id = $core->user->get_user_id(htmlspecialchars($new_user));
	
	#
	if($new_team_member_id && $pid_check == $pid)
	{
		$x = $core->todo->add_user_to_shared_project($pid, $new_team_member_id, $user_id);
		
		echo ($x) ? htmlspecialchars($new_user) : "";
	}
	$core->close();
}

if ($new_project && !empty($_POST['project_name']))
{
	$core->db->sql('INSERT INTO `project_list` (`name` ,`user_id`) VALUES ("' . $_POST['project_name'] . '",  "' . $user_id . '");', __FILE__, __LINE__);
}

if ($project_id) 
{
	$core->session->put('pid', (int) $_GET['project_id']);
	
	# unset the tid
	$todo_id = false;
}

if ($new_todo && !empty($_POST['todo_title']) && is_numeric($core->session->get('pid')))
{
	$content = "";
	$core->todo->add_todo ($_POST['todo_title'], $content, $core->session->get('pid'), $user_id);
}

# change content of todo
if ($change_topic)
{
	$content 	= (isset($_POST['content'])) ? htmlspecialchars($_POST['content']) : false;
	$title 		= (isset($_POST['title'])) ? htmlspecialchars($_POST['title']) : false;

	$core->todo->change_todo($todo_id, $title, $content, $user_id);
}

$own = $core->db->sql('SELECT * FROM project_list where `user_id` = "' . $user_id . '";', __FILE__, __LINE__);
$shared = $core->db->sql('select * from project_shared join project_list on project_list.id = project_shared.project_id where project_shared.user_id = "' . $user_id . '" ;');

$core->view->projects = array_merge($own, $shared);

# an open active project
if (is_numeric($core->session->get('pid')))
{
	$pid = $core->session->get('pid');
	
	# get owner of project
	$owner = $core->db->sql('SELECT user_id from project_list where id = "' . $pid . '" limit 0,1;', __FILE__, __LINE__);
	
	# if owner is user, show this, otherwise show username of owner
	$core->view->owner = ($owner['user_id'] == $user_id) ? false : $core->user->get_user_name($owner['user_id']);
	
	
	# get other users
	$user_who_can_view = $core->db->sql('select user_data.username from project_shared JOIN user_data on user_data.id = project_shared.user_id where project_id = "' . $pid . '" && user_id != "' . $user_id . '";');
	$core->view->other_users = $user_who_can_view;
	
	# get todo's
	$core->view->todos = $core->todo->get_todo_list ($pid, $user_id);
}


if ($todo_id) 
{
	$core->view->topic = $core->todo->get_todo ($todo_id, $user_id);
}

# output for the header
$core->view->logged_in = true;
$core->view->user_name = $core->user->get_user_name();
$core->view->use_page('header');

$core->view->pid = $core->session->get('pid');
$core->view->tid = $todo_id;

# output for main
$core->view->use_page('project_list');
$core->view->use_page('todo_list');
$core->view->use_page('detail_todo');

# output for footer
$core->view->use_page('footer');

# show output to screen
$core->close();
?>
