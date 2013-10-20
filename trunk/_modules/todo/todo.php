<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

/**
* user class
* @abstract
*/
final class todo
{	
	public
		$core,
		$user_owned_projects = array()
		;
	
	
	/**
	* initialize
	* @param object $core
	*/
	function __construct($core)
	{
		// reference to the core object
		$this->core = $core;
	}

	/*
	* check if user is owner
	*/
	public function is_owner ($pid, $user_id)
	{
		# already fetched
		if (in_array($pid, $this->user_owned_projects))
		{
			return true;
		}
		# not yet fetched
		else if ($this->core->db->sql('SELECT id FROM project_list WHERE user_id = "' . (int) $user_id . '" && id ="' . (int) $pid . '" limit 1;', __FILE__, __LINE__)) 
		{
			return true;
		}
		return false;
	}
	
	/*
	*	general project list available to the user
	*/
	public function get_project_list ($user_id) 
	{
		$own = $this->core->db->sql('
			SELECT 
				id, user_id, name, 
				"1" as owner,
				(SELECT count(user_id) FROM project_shared WHERE project_list.id = project_shared.project_id) as shared
			FROM 
				project_list
			WHERE 
				`user_id` = "' . $user_id . '";
			', __FILE__, __LINE__, 'ASSOC');
			
		$shared = $this->core->db->sql('
			SELECT 
				project_list.id, project_list.user_id, project_list.name, 
				"0" as owner,
				"1" as shared
			FROM 
				project_shared 
			JOIN 
				project_list 
			ON 
				project_list.id = project_shared.project_id 
				
			WHERE 
				project_shared.user_id = "' . $user_id . '";
				
			', __FILE__, __LINE__, 'ASSOC');

		# save list
		$own_cache = array();
		foreach ($own as $data)
		{
			$own_cache[] = $data['id'];
		}
		
		$this->user_owned_projects = $own_cache;
		return array_merge($own, $shared);
	}
	
	/*
	* add user to shared to_do list
	*/
	public function add_user_to_shared_project ($pid, $adding_user, $user_id)
	{		
		if ($adding_user != $user_id)
		{
			# ignore : in case it already exists (there is a unique on project_id and user_id)
			return $this->core->db->sql('INSERT IGNORE INTO `project_shared` (`project_id` ,`user_id`) VALUES ("' . (int) $pid .'",  "'. (int) $adding_user .'");');
		}
		return false;
	}

	/*
	* remove team members
	*/
	public function remove_user_from_shared_project ($pid, $remove_user, $user_id)
	{
		# check if user is owner
		if ($this->is_owner($pid, $user_id))
		{
			$this->core->db->sql('DELETE FROM `project_shared` WHERE `project_id` = "'. (int) $pid .'" AND `user_id` = "' . (int) $remove_user . '" limit 1;');
		}
	}
	
	/*
	* get todo list
	* these get sorted using a string; not the best method;
	* but the easiest, and only uses 1 query to change the entire list
	*/
	public function get_todo_list ($pid, $user_id) 
	{
		if ($this->is_owner($pid, $user_id))
		{
			$todos_list = $this->core->db->sql('
				SELECT 
					title, id 
				FROM 
					todo_list 
				WHERE 
					(
						project = "' . $pid . '"
					) 
					;', __FILE__, __LINE__);

		}
		else
		{
			$todos_list = $this->core->db->sql('
				SELECT 
					title, id 
				FROM 
					todo_list 
				WHERE 
					(
						project = "' . $pid . '" || 
						project IN (select project_id from project_shared where user_id="'. $user_id .'") 
					) 
					;', __FILE__, __LINE__);
		}
		
		# array(key, data data) to key => array(data)
		$todos = array();
		foreach ($todos_list as $todo)
		{
			$todos[$todo['id']] = $todo;
		}
		
		$user_sort = $this->core->db->sql('SELECT user_sort FROM user_sort WHERE user_id= "' . $user_id . '" && project_id= "' . $pid . '" limit 1;', __LINE__, __FILE__);
		if ($user_sort)
		{
			$sorted_data = array();
			$user_todo_sorted = $user_sort['user_sort'];
			
			foreach (explode(",", $user_todo_sorted) as $sort_key) 
			{
				if (array_key_exists ($sort_key, $todos))
				{
					$sorted_data[] = $todos[$sort_key];
					unset ($todos[$sort_key]);
				}
				/*
				should not happen
				*/				
				else
				{
					echo "canno find key : " . $sort_key;
				}
				/*
				*/
			}
			$sorted_data = array_merge($sorted_data, $todos);
		}
		# no user sort
		else
		{
			$sorted_data = $todos;
		}
		return $sorted_data;
	}	
	
	/*
	* add a todo
	*/
	public function add_todo ($title, $content, $pid, $user_id) 
	{
		return $this->core->db->sql('INSERT INTO `todo_list` (`title` ,`project`, `content`, `status`, `user`) VALUES ("' . $title . '",  "'. $pid .'", "'. $content .'", "0",  "' . $user_id . '");', __FILE__, __LINE__);
	}
	
	/**
	* change a todo on detailed level
	*/
	public function change_todo ($todo_id, $title, $content, $user_id)
	{
		if($content && $title)
		{
			return $this->core->db->sql('UPDATE `todo_list` SET `content` = "' . $content . '", `title` = "' . $title . '" WHERE `id`="' . $todo_id . '" && (user = "' . $user_id . '" || project IN (select project_id from project_shared where user_id= "'.$user_id.'") ) limit 1;', __FILE__, __LINE__);
		} 
		else if ($content)
		{
			return $this->core->db->sql('UPDATE `todo_list` SET `content` = "' . $content . '" WHERE `id`="' . $todo_id . '" && (user = "' . $user_id . '" || project IN (select project_id from project_shared where user_id= "'.$user_id.'") )  limit 1;', __FILE__, __LINE__);
		}
		else if ($title)
		{
			return $this->core->db->sql('UPDATE `todo_list` SET `title` = "' . $title . '" WHERE `id`="' . $todo_id . '" && 	(user = "' . $user_id . '" || project IN (select project_id from project_shared where user_id= "'.$user_id.'") ) limit 1;', __FILE__, __LINE__);
		}
	}
	
	/**
	* change todo status
	* status : 0 (new)
	* status : 1 (finished)
	* status : 2 (...)
	*/
	public function change_todo_status ($todo_id, $status, $user_id)
	{
		# update if user is 'owner of todo'
		return $this->core->db->sql('UPDATE `todo_list` SET `status` =  "'. $status .'" WHERE `id` = "' . (int) $todo_id . '" && `user_id` = "'. (int) $user_id .'" limit 1;');
		
	}
	
	/*
	* get todo
	*/
	public function get_todo ($todo_id, $user_id)
	{
		return $this->core->db->sql('
								SELECT 
									* 
								FROM 
									todo_list 
								WHERE
									id= "' . $todo_id . '" && 
									(
										user = "' . $user_id . '" 
										|| 
										project IN (SELECT project_id from project_shared where user_id = "'. $user_id .'")
									) 
									limit 0,1;',
									__FILE__,
									__LINE__
							);
	}
}

?>