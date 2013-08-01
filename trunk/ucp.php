<?php
#   Svenn D'Hert
include('_main/main_frame.php');
 
# initialise frame
$core = new core(
					array('title' => 'UCP')
				);

# load modules
$core->load_modules(array('database', 'sessions', 'users', 'view', 'form'));

# log the user out if requested
if(isset($_GET['byebye']))
{
	$core->user->logout();
	$core->view->set('report', 'Bye, cya later :).');
	$core->view->use_page('report_page');
}

# check if user is logged in
if ($core->user->is_logged() && !isset($_GET['byebye'])){ header("Location: index.php");}

# main pointer
$register_new_user 	= (isset($_GET['reg']) ) ? true : false;
$login_user 		= (isset($_GET['log']) ) ? true : false;

# output for the header

$core->view->logged_in = false;
$core->view->use_page('header');

# reg new user
if ($register_new_user)
{
	# register pointer
	$data_entered = (isset($_GET['data'])) ? true : false;
	
	# load design for new registered user (congratz!)
	if ($data_entered)
	{
		$myform = new form($core, array('username' => 'string', 'password' => 'string', 'email' => 'email'));
		
		if (isset($myform->result['username']) && isset($myform->result['password']) && $myform->result['email'])
		{
			if (!$core->user->registration ($myform->result['username'], $myform->result['password'], $myform->result['email']))
			{
				$core->view->set('error', 'Registration failed, most likely your username already excist please <a href="ucp.php?reg">try again</a>.');
				$core->view->use_page('error_page');
			}
			else
			{
				$core->view->set('report', 'Registration succes, you are now logged in :).');
				$core->view->use_page('report_page');
				# initial cron run
			}
		}
		else
		{
			$core->view->set('error', 'Some of the entered values are not valid, please get back and <a href="ucp.php?reg">retry</a>.');
			$core->view->use_page('error_page');
		}
	}
	# load design for new user
	else
	{
		$core->view->use_page('new_user');
	}
}

# login
if ($login_user)
{
	# login pointer
	$data_entered = (isset($_GET['data'])) ? true : false;
	
	# load design for new registered user (congratz!)
	if ($data_entered)
	{
		$myform = new form($core, array('username' => 'string', 'password' => 'string'));
		
		if (isset($myform->result['username']) && isset($myform->result['password']))
		{
			if ($core->user->login($myform->result['username'], $myform->result['password']))
			{
				$core->view->set('report', 'Login succes, <a href="index.php?">welcome back, aboard sir!</a>.');
				$core->view->use_page('report_page');
			}
			else
			{
				$core->view->set('error', 'No valid data entered, <a href="ucp.php?log">try again</a>.');
				$core->view->use_page('error_page');
			}
		}
		else
		{
			$core->view->set('error', 'No valid data entered, <a href="ucp.php?log">try again</a>.');
			$core->view->use_page('error_page');
		}
	}
	# load design for new user
	else
	{
		$core->view->use_page('user_login');
	}
} 

if (!$login_user && !$register_new_user) 
{
	$core->view->use_page('login_or_reg');
}

# output for footer
$core->view->use_page('footer');

# show output to screen
$core->close();