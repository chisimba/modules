<h1> TEST </h1>

<a href="<?php echo $this->uri(array('action' => 'createassessment'));?>">
Create Assessment </a><br><br>

<form action="<?php echo $this->uri(array('action' => 'submitassessment'));?>" method="post"  enctype="multipart/form-data">
<input type="file" name="pdata"><br>
Paper Title<input type="text" name="ptl"><br>
Type<input type="text" value="2" name="ptype"><br>
User Email<input type="text" name="uem"><br>
Firstname<input type="text" value="Student" name="ufn"><br>
Surname<input type="text" value="student" name="uln"><br>
<input type="submit"><br><br>

<a href="<?php echo $this->uri(array('action' => 'submitassessment'));?>">
Submit Assessment </a><br><br>


<form action="<?php echo $this->uri(array('action' => 'apilogin'));?>" method="post">
Firstname<input type="text" name="firstname"><br>
Surname<input type="text" name="lastname"><br>
Password<input type="text" name="password"><br>
Email<input type="text" name="email"><br>
<input type="submit"><br><br>
