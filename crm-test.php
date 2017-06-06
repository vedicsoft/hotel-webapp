<?php
include "crm.php";

$userdata = array(
 array(
   "name" => "first_name",
   "value" => "gajan"
  ),
 array(
   "name" => "last_name",
   "value" => "gajan"
  ),
 array(
   "name" => "email1",
   "value" => "gajan@gmail.com"
  ),
array(
   "name" => "assigned_user_id",
   "value" => "1"
  ),
);

echo registerCRMUser($userdata);


