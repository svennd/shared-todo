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
		$core
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
	*	Functions that work on todo's
	*/
	
	/*
	* get todo list
	* these get sorted using a string; not the best method;
	* but the easiest, and only uses 1 query to change the entire list
	*/
	public function get_todo_list ($pid, $user_id) 
	{
		$todos_list = $this->core->db->sql('SELECT title, id FROM todo_list WHERE project = "' . $pid . '" && user = "' . $user_id . '" && status = "0";', __FILE__, __LINE__);
		
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
			$this->core->db->sql('UPDATE `todo_list` SET `content` = "' . $content . '", `title` = "' . $title . '" WHERE `id`="' . $todo_id . '" && user = "' . $user_id . '" limit 1;', __FILE__, __LINE__);
		} 
		else if ($content)
		{
			$this->core->db->sql('UPDATE `todo_list` SET `content` = "' . $content . '" WHERE `id`="' . $todo_id . '" && user = "' . $user_id . '" limit 1;', __FILE__, __LINE__);
		}
		else if ($title)
		{
			$this->core->db->sql('UPDATE `todo_list` SET `title` = "' . $title . '" WHERE `id`="' . $todo_id . '" && user = "' . $user_id . '" limit 1;', __FILE__, __LINE__);
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