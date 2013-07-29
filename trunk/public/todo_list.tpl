<div class="span4">
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

<ul class="nav nav-list">
<li id="head" class="nav-header head">User's :</li>
<li>You</li>
</ul>
<br/>
<form action="?add_user" method="post" id="add_user" name="add_user">
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