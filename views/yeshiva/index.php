<div class="bigcontainer">
    
<div class="main">
    <h2>Students</h2><span><form id="btn" action='<?php echo config::URL  ?>/yeshiva/newstudent' method="post"><button type="submit" name="action" value="newstudent">+</button></form></span><hr>
    <div class="scroll"><?php echo $this->studentlist ?></div>   
</div>
<div class="main">
    <h2>Courses</h2><span><form id="btn" action='<?php echo config::URL  ?>/yeshiva/newcourse' method="post"><button type="submit" name="action" value="newcourse">+</button></form></span><hr>
    <div class="scroll"><?php echo $this->courselist ?></div>

</div>


<!--</div>-->
