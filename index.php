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
$add_user		= (isset($_GET['add_user']))? true : false;

# core settings
$user_id = $core->user->get_user_id();

# output for the header
$core->view->use_page('header');

if ($add_user)
{
	$new_user 	= $_POST['user'];
	$pid_check 	= $_POST['pid'];
	$pid		= $core->session->get('pid');
	
	# is it a user ?
	$new_team_member_id = $core->user->get_user_id(htmlspecialchars($new_user));
	
	if($new_team_member_id && $pid_check == $pid)
	{
		
		$core->db->sql('INSERT INTO `project_shared` (`project_id` ,`user_id`) VALUES ("' . (int) $pid .'",  "'. (int) $new_team_member_id .'");');
	}
	else
	{
		// user not found error
	}
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
	$core->db->sql('INSERT INTO `todo_list` (`title` ,`project` , `user`) VALUES ("' . $_POST['todo_title'] . '",  "'. $core->session->get('pid') .'",  "' . $user_id . '");', __FILE__, __LINE__, 'ASSOC');
}

# change content of todo
if ($change_topic)
{
	$content 	= (isset($_POST['content'])) ? htmlspecialchars($_POST['content']) : false;
	$title 		= (isset($_POST['title'])) ? htmlspecialchars($_POST['title']) : false;

	if($content && $title)
	{
		$core->db->sql('UPDATE `todo_list` SET `content` = "' . $content . '", `title` = "' . $title . '" WHERE `id`="' . $todo_id . '" && user = "' . $user_id . '" limit 1;', __FILE__, __LINE__);
	} 
	else if ($content)
	{
		$core->db->sql('UPDATE `todo_list` SET `content` = "' . $content . '" WHERE `id`="' . $todo_id . '" && user = "' . $user_id . '" limit 1;', __FILE__, __LINE__);
	}
	else if ($title)
	{
		$core->db->sql('UPDATE `todo_list` SET `title` = "' . $title . '" WHERE `id`="' . $todo_id . '" && user = "' . $user_id . '" limit 1;', __FILE__, __LINE__);
	}
}

$own = $core->db->sql('SELECT * FROM project_list where `user_id` = "' . $user_id . '";', __FILE__, __LINE__);
$shared = $core->db->sql('select * from project_shared join project_list on project_list.id = project_shared.project_id where project_shared.user_id = "' . $user_id . '" ;');
// $core->view->projects = $own;
$core->view->projects = array_merge($own, $shared);

# an open active project
if (is_numeric($core->session->get('pid')))
{
	# get other users
	$core->db->sql('select user_data.username from project_shared JOIN user_data on user_data.id = project_shared.user_id where project_id = "' . $core->session->get('pid') . '";');
	$core->view->other_users = $core->db->result;
	
	# get todo's
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
