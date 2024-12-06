<?php
require_once "ProfileDosen.php";
require_once "../../template/TemplateDosenAdmin.php";

class ProfileDosenAdmin extends ProfileDosen implements IProfile
{
     // get profile dosen
     public function getProfileUser($id_dosen)
     {
          return parent::getProfileUser($id_dosen);
     }

     public function getUserById($username)
     {
          return parent::getUserById($username);
     }

     // get image user
     function getProfileImagePath($username)
     {
          return parent::getProfileImagePath($username);
     }

     // get semua data user
     function getUserData($username)
     {
          return parent::getUserData($username);
     }

     public function profile()
     {
          return parent::profile();
     }
}
$profile = new ProfileDosenAdmin();
?>