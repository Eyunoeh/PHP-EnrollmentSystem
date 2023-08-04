<?php
include '../../Database/mysql_query.php';


$nested_array = get_course_subjects_info(1);
foreach ($nested_array as $subject){
    $id = $subject[0];
    $name = $subject[1];
    $prof= $subject[3];
    echo "ID: $id, Name: $name, prof:$prof<br>";
}
