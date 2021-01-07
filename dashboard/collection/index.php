<?php
  require('../../config/config.php');
  require '../../api/func.php';
  $query = $con->prepare("SELECT * FROM psits_collections WHERE event = {$_GET['id']}");
  $query->execute();
  $collection = $query->fetchAll(PDO::FETCH_ASSOC);
 ?>
 <script type="text/javascript">
  $(document).ready( function () {
    $('#collection').DataTable();
  });
</script>
<table id="collection">
  <thead>
    <tr>
      <th>Student ID</th>
      <th>Name</th>
      <th>Course</th>
      <th>Amount</th>
      <th>Collector</th>
      <th>Date</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($collection as $key => $value): ?>
      <tr>
        <td><?php echo $value['student_id'] ?></td>
        <td>
          <a href="/dashboard/student/?action=student&all&id=<?php echo getStudentId($value['student_id']) ?>" target="_blank">
          <?php echo $value['student_name'] ?></a></td>
        <td><?php echo $value['course'] ?></td>
        <td>
          <img src="img/peso.svg" alt="" width="10">
          <?php echo $value['payment'] ?></td>
        <td><?php echo $value['encoded_by'] ?></td>
        <td><?php echo $value['_date'] ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
