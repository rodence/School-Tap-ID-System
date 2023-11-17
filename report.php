<?php require_once 'includes/db_conn.php';
include 'includes/header.php';
include 'includes/navbar.php';




?>
<br>
<br>
<br>
<br>
<center>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3>Generate Report</h3>	
			</div>
			<!-- /panel-heading -->
			<div class="panel-body">
				
				<form class="form-horizontal" action="includes/getAttendanceReport.php" method="post" id="getOrderReportForm">
				  <div class="form-group">
				    <label for="startDate" class="col-sm-2 control-label">Start Date</label>
				    <div class="col-sm-10">
				      <input type="date" class="form-control" id="startDate" name="startDate" placeholder="Start Date" />
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="endDate" class="col-sm-2 control-label">End Date</label>
				    <div class="col-sm-10">
				      <input type="date" class="form-control" id="endDate" name="endDate" placeholder="End Date" />
				    </div>
				  </div>
				  <div class="form-group">
				    <div class="col-sm-offset-2 col-sm-10">
				      <button type="submit" class="btn btn-primary" id="generateReportBtn"> <i class="glyphicon glyphicon-ok-sign"></i> Generate Report</button>
				    </div>
				  </div>
				</form>

			</div>
			<!-- /panel-body -->
		</div>
	</div>
	<!-- /col-dm-12 -->
</div>
<!-- /row -->
</center>

<script src="custom/js/report.js"></script>

<?php require_once 'includes/footer.php'; ?>