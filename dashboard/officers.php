<?php require('../config/config.php') ?>
<?php require('../api/func.php') ?>
<?php
secure(['admin-only'=>true]);
  $access = $_GET['access'];
  $sql = sprintf('SELECT * FROM psits_officers WHERE access = %d',$access);
  $officer = $con->prepare($sql);
  $officer->execute();
  $officer = $officer->fetchAll(PDO::FETCH_ASSOC);
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
      <th>Position</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($officer as $key => $value): ?>
      <tr onclick="window.open('/dashboard/officer?action=officer&all&id=<?php echo $value['officer_id'] ?>','_blank')">
        <td><?php echo $value['officer_id'] ?></td>
        <td><?php echo $value['full_name'] ?></td>
        <td><?php echo $value['position'] ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
