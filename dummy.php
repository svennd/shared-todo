<?php
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

// $arr = array("options"=> array('option','option1'));

// echo json_encode($arr);
// echo '{"options": ["Option 1", "Option 2","Option 3","Option 4","Option 5"]}';
echo '["Option 1", "Option 2","Option 3","Option 4","Option 5"]';
?>