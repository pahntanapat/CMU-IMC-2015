<?php
require_once 'config.inc.php';
require_once 'class.SesAdm.php';

$sess=SesAdm::check();
if(!$sess) Config::redirect('admin.php','you are not log in.');
if(!$sess->checkPMS(SesAdm::PMS_PARTC)) Config::redirect('home.php','you don\'t have permission here.');

require_once 'class.Team.php';
require_once 'class.Member.php';
require_once 'class.Message.php';

function approveTeam(){
	ob_start();
	
?>
<h3>Team's name: </h3>
 <ul class="tabs vertical" data-tab>
   <li><a href="#t">Approve Team's info</a></li>
   <li><a href="#p">Approve 's info</a></li>
 </ul>
<div class="tabs-content">
<div class="content active" id="t">
  <table width="100%" border="0">
    <tr>
      <th scope="col">Form</th>
      <th scope="col">Detail</th>
    </tr>
    <tr>
      <th scope="row">Email</th>
      <td>1</td>
    </tr>
    <tr>
      <th scope="row">Team's name</th>
      <td>1</td>
    </tr>
    <tr>
      <th scope="row">Medical school</th>
      <td>1</td>
    </tr>
    <tr>
      <th scope="row">University</th>
      <td>1</td>
    </tr>
    <tr>
      <th scope="row">Address</th>
      <td>1</td>
    </tr>
    <tr>
      <th scope="row">Country</th>
      <td>1</td>
    </tr>
    <tr>
      <th scope="row">Med school's phone</th>
      <td>1</td>
    </tr>
  </table>

</div>
<div class="content" id="p">
  <table width="100%" border="0">
    <tr>
      <th scope="col">Form</th>
      <th scope="col">Detail</th>
    </tr>
    <tr>
      <th scope="col">Participant No.</th>
      <td>1</td>
      </tr>
    <tr>
      <th scope="row">Title</th>
      <td>1</td>
      </tr>
    <tr>
      <th scope="row">Firstname</th>
      <td>1</td>
      </tr>
    <tr>
      <th scope="row">Middlename</th>
      <td>1</td>
      </tr>
    <tr>
      <th scope="row">Lastname</th>
      <td>1</td>
      </tr>
    <tr>
      <th scope="row">Gender</th>
      <td>1</td>
      </tr>
    <tr>
      <th scope="row">Med Student Year</th>
      <td>1</td>
      </tr>
    <tr>
      <th scope="row">Birth</th>
      <td>1</td>
      </tr>
    <tr>
      <th scope="row">Nationality</th>
      <td>1</td>
      </tr>
    <tr>
      <th scope="row">Phone</th>
      <td>1</td>
      </tr>
    <tr>
      <th scope="row">Email</th>
      <td>1</td>
      </tr>
    <tr>
      <th scope="row">Facebook</th>
      <td>1</td>
      </tr>
    <tr>
      <th scope="row">Twitter</th>
      <td>1</td>
      </tr>
    <tr>
      <th scope="row">Emergency contact</th>
      <td>1</td>
      </tr>
    <tr>
      <th scope="row">Religion</th>
      <td>1</td>
      </tr>
    <tr>
      <th scope="row">Preferred cuisine</th>
      <td>1</td>
      </tr>
    <tr>
      <th scope="row">Allergy</th>
      <td>1</td>
      </tr>
    <tr>
      <th scope="row">Underlying disease</th>
      <td>1</td>
      </tr>
    <tr>
      <th scope="row">Other requirements</th>
      <td>1</td>
      </tr>
    <tr>
      <th scope="row">Shirt size</th>
      <td>1</td>
      </tr>
  </table>
</div>
</div>
<?php
	return ob_get_clean();
}

 ?>