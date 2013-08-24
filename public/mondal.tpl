<div class="modal fade" id="setting_mondal">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h3>User Management</h3>
  </div>
  <div class="modal-body">
	<table class="table">
	<thead>
		<tr><th>#</th><th>User</th><th>action</th><th>Owner</th></tr>
	<thead>
	<tbody>
	<?php
		echo ($owner) ? "<tr><td>1</td><td>". $owner . "</td><td>&nbsp;</td><td><i class='icon-ok'></td></tr>" : "<tr><td>1</td><td>". $user_name . " (You)</td><td>&nbsp;</td><td><i class='icon-ok'></td></tr>";
		if ($other_users)
		{
			$i = (!$owner) ? 2 : 1;
			foreach ($other_users as $user)
			{
				echo "<tr><td>" . $i . "</td><td>" . $user['username'] . "</td><td>" . ((!$owner) ? '<i class="icon-remove">' : '') ."</td><td>&nbsp;</td></tr>";
				$i++;
			}
		}
	?>
	</tbody>
	</table>
	<br/>
	<form action="?add_user" method="post" id="add_user" name="add_user" autocomplete="off">
		<div class="input-append">
		  <input class="input-medium" autocomplete="off" name="user" id="typeahead" type="text">
		  <input type="hidden" name="pid" value="<?php echo $pid; ?>" />
		  <button type="submit" id="add_user_submit" class="btn">Add</button>
		</div>
	</form>
  </div>
</div>