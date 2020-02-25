<?php
  require_once("../dbconnect.php");

  $projid = filter_input(INPUT_POST, 'projid', FILTER_VALIDATE_INT);
  $workid = filter_input(INPUT_POST, 'workid', FILTER_VALIDATE_INT);

  $queryWork = "SELECT * FROM workcompleted
              WHERE workid = :workid
              ORDER BY dateperformed";
  $stmt = $db->prepare($queryWork);
  $stmt->bindValue(':workid', $workid);
  $stmt->execute();
  $work = $stmt->fetchAll();
  $stmt->closeCursor();
?>

<!DOCTYPE html>
<html>
  <head>
  <link rel="stylesheet" type="text/css" href="../css.css">
  </head>
  <body>
   <?php include("../navbar.php"); ?>
    <h1>Update Completed Work</h1>
    <div class="form-style-6">
      <h1>Enter Work Information</h1>
        <?php foreach($work as $w){ ?>
        <form action="updatework.php" method="post">
          <input type="text" name="workname" placeholder="Work Name" value="<?php echo $w['workname']; ?>"/>
          <input type="text" name="workdate" placeholder="Date yyyy/mm/dd" value="<?php echo $w['dateperformed']; ?>">
          <textarea type="text" name="workdesc" placeholder="Work Description" ><?php echo $w['workdesc']; ?></textarea>
          <textarea type="text" name="workcomments" placeholder="Comments"><?php echo $w['workcomments']; ?></textarea>
          <input type="hidden" name="projid" value="<?php echo $projid; ?>">
          <input type="hidden" name="workid" value="<?php echo $workid; ?>">
          <input type="submit" value="Update Work"/>
        </form>                             
        <?php } ?>
      </div>
   </body>
</html>