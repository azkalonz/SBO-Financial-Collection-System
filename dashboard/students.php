<?php require('../config/config.php') ?>
<?php require('../api/func.php') ?>
<?php
  $course = $_GET['course'];
  $sql = $course!=''?sprintf('SELECT * FROM ccs_students WHERE course = "%s"',$course):'SELECT * FROM ccs_students';
  $students = $con->prepare($sql);
  $students->execute();
  $students = $students->fetchAll(PDO::FETCH_ASSOC);
?>
<script type="text/javascript">
  $(document).ready( function () {
    $('#users').DataTable();
  });
</script>
<table id="users" style="width: 100%;">
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Yr. lvl</th>
      <th>Email</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($students as $key => $value): ?>
      <tr onclick="window.open('/dashboard/student?action=student&all&id=<?php echo $value['id'] ?>','_blank')">
        <td><?php echo $value['student_id'] ?></td>
        <td><?php echo $value['full_name'] ?></td>
        <td><?php echo $value['_year'] ?></td>
        <td><?php echo $value['email'] ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
