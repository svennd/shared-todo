<?php
#   Svenn D'Hert
include('_main/main_frame.php');
 
# initialise frame
$core = new core(
					array('title' => 'Mijn Pagina')
				);

# load modules
$core->load_modules(array('view', 'database', 'sessions', 'users'));

# not logged
if (!$core->user->is_logged()) {header("Location: ucp.php");}

# head input
$new_project 	= (isset($_GET['add_project'])) ? true : false;
$new_todo 		= (isset($_GET['add_todo'])) 	? true : false;
$project_id 	= (isset($_GET['project_id'])) 	? (int) $_GET['project_id'] : false;
$todo_id 		= (isset($_GET['todo_id'])) 	? (int) $_GET['todo_id'] : false;
$change_topic	= (isset($_GET['change_topic']))? true : false;

# core settings
$user_id = $core->user->get_user_id();

# output for the header
$core->view->use_page('header');

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
	$core->db->sql('INSERT INTO `todo_list` (`title` ,`project` , `user`) VALUES ("' . $_POST['todo_title'] . '",  "'. $core->session->get('pid') .'",  "' . $user_id . '");', __FILE__, __LINE__, 'ASSOC');
}

$core->db->sql('SELECT * FROM project_list where `user_id` = "' . $user_id . '";', __FILE__, __LINE__);
$core->view->projects = $core->db->result;

if (is_numeric($core->session->get('pid')))
{
	$data = $core->db->sql('SELECT title, id FROM todo_list where project = ' . $core->session->get('pid') . ' && user = ' . $user_id . ';', __FILE__, __LINE__);
	# user sort (ugly)
	
	$todos = array();
	foreach ($core->db->result as $id)
	{
		$todos[$id['id']] = $id;
	}
	
	$core->db->sql('select user_sort from user_sort where user_id= "' . $user_id . '" && project_id= "' . $core->session->get('pid') . '" limit 1;', __LINE__, __FILE__, 'ASSOC');
	if ($core->db->result)
	{
		$sorted_data = array();
		foreach (explode(",", $core->db->result['0']['user_sort']) as $sort_key) 
		{
			if (array_key_exists ($sort_key, $todos))
			{
				$sorted_data[] = $todos[$sort_key];
				unset ($todos[$sort_key]);
			}
			else
			{
				echo "canno find key : " . $sort_key;
			}
		}
		$sorted_data = array_merge($sorted_data, $todos);
	}
	# no user sort
	else
	{
		$sorted_data = $todos;
	}
	$core->view->todos = $sorted_data;
}

# change content of todo
if ($change_topic)
{
	$core->db->sql('UPDATE `todo_list` SET `content` = "' . $_POST['content'] . '" WHERE `id`="' . $todo_id . '";');
}

if ($todo_id) 
{
	$core->view->topic = $core->db->sql('SELECT * FROM todo_list where id= "' . $_GET['todo_id'] . '" && user = "' . $user_id . '" limit 0,1;');
}

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
