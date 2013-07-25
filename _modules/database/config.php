<?php
/**
* @package SoFramwork
* @copyright 2010 Svenn D'Hert
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
*/

// no direct acces
if ( !isset($this) ){ exit('direct_acces_not_allowed'); }

// configuration of database
$host 		= 'localhost';
$user 		= 'root';
$password 	= '';
$database 	= 'shared_todo';

// database type or method
$db_type 	= 'mysql';	# mysql, mysqli

?>