<?php
require 'ProfileDpa.php';

class ProfileDpaAdmin extends ProfileDPA
{

     public function getProfileUser($id_dosen)
     {
          return parent::getProfileUser($id_dosen);
     }

     public function getUserById($username)
     {
          return parent::getUserById($username);
     }

     public function profile()
     {
          return parent::profile();
     }
}
$profile = new ProfileDpaAdmin();