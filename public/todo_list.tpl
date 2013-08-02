<div class="span4">
<a class="btn btn-mini" href="#setting_mondal" data-toggle="modal"><i class="icon-user"></i> Gebruikers</a> 
<br/>
<br/>
<div class="well well-small" >		  
<?php if (is_numeric($pid)) : ?>
<input type="hidden" id="pid" name="pid" value="<?php echo $pid; ?>" />

<ul id="sortable" class="nav nav-list">
<li id="head" class="nav-header head"><img src='public/loader.gif' class="load_bar" style="display:none;"/> Todo's :</li>
<?php
	if( $todos ) 
	{
		foreach ($todos as $todo) {
			if($todo['id'] == $tid){}

			echo 
					"<li id='" . $todo['id'] . "' ". (($todo['id'] == $tid) ? "class='active'" : "").">
						<a href='?todo_id=" . $todo['id'] . "'><i class='icon-resize-vertical'></i>". $todo['title'] ."</a>
					</li>";

		}
	}
?>
</ul>
<br/>
<form action="?add_todo" method="post" id="add_todo" name="add_todo">
	<div class="input-append">
	  <input class="input-medium" name="todo_title" id="todo_title" type="text">
	  <button type="submit" class="btn">Add</button>
	</div>
</form>

<?php else: ?>
<p>
	Select your project
</p>
<?php endif; ?>
</div>
</div>

<div class="modal fade" id="setting_mondal">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h3>Gebruikers Beheer</h3>
  </div>
  <div class="modal-body">
	<ul id="user_list" class="nav nav-list">
	<li id="head" class="nav-header head">User's :</li>
	<li>You</li>
	<?php
		if ($other_users)
		{
			foreach ($other_users as $user)
			{
				echo "<li>" . $user['username'] . "</li>";
			}
		}
	?>
	</ul>
	<br/>
	<form action="?add_user" method="post" id="add_user" name="add_user" autocomplete="off">
		<div class="input-append">
		  <input class="input-medium" autocomplete="off" name="user" id="typeahead" type="text">
		  <input type="hidden" name="pid" value="<?php echo $pid; ?>" />
		  <button type="submit" id="add_user_submit" class="btn">Add</button>
		</div>
	</form>
  </div>
  <!--
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
    <a href="#" class="btn btn-primary">Save Changes</a>
  </div>
  -->
</div>