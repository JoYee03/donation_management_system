<?php
include("header.php");

if(isset($_GET['delid']) && is_numeric($_GET['delid'])) {
    $delid = intval($_GET['delid']);
    $stmt = mysqli_prepare($con, "DELETE FROM fund_collection WHERE fund_collection_id=?");
    mysqli_stmt_bind_param($stmt, "i", $delid);
    mysqli_stmt_execute($stmt);
    
    if(mysqli_affected_rows($con) == 1) {
        echo "<script>alert('Fund collection record deleted successfully.');</script>";
        echo "<script>window.location='viewfundcollection.php';</script>";
    } else {
        echo "<script>alert('Error deleting record: " . mysqli_error($con) . "');</script>";
    }
    mysqli_stmt_close($stmt);
}
?>
</header>

<div id="about" class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title">
                    <center><h2 class="title">View Fund Collection</h2></center>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="numbers" class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="number">
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Fund Raiser detail</th>
                                <th>Donor detail</th>
                                <th>Paid Date</th>
                                <th>Payment Detail</th>
                                <th style='text-align: right;'>Paid Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Base query
                            $sql = "SELECT * FROM fund_collection 
                                    LEFT JOIN donor ON donor.donor_id=fund_collection.donor_id 
                                    LEFT JOIN fund_raiser ON fund_raiser.fund_raiser_id=fund_collection.fund_raiser_id 
                                    WHERE fund_collection.status='Active'";
                            
                            // Add donor filter if logged in as donor
                            if(isset($_SESSION['donor_id']) && is_numeric($_SESSION['donor_id'])) {
                                $donor_id = intval($_SESSION['donor_id']);
                                $sql .= " AND fund_collection.donor_id='$donor_id'";
                            }
                            
                            $qsql = mysqli_query($con, $sql);
                            if(!$qsql) {
                                die("Database error: " . mysqli_error($con));
                            }
                            
                            while($rs = mysqli_fetch_assoc($qsql)) {
									echo "<tr>
										<td style='text-align: left;'>
											<b>" . htmlspecialchars($rs['title'] ?? '') . "</b><br>
											<b>Starts -</b> " . (!empty($rs['start_date']) ? date("d-M-Y", strtotime($rs['start_date'])) : 'N/A') . "<br>
											<b>Ends -</b> " . (!empty($rs['end_date']) ? date("d-M-Y", strtotime($rs['end_date'])) : 'N/A') . "<br>
										</td>
										<td style='text-align: left;'>
											<b>" . htmlspecialchars($rs['name'] ?? '') . "</b><br>
											" . htmlspecialchars($rs['address'] ?? '') . ",<br>
											" . htmlspecialchars($rs['city'] ?? '') . "-" . htmlspecialchars($rs['pin_code'] ?? '') . "<br>
											<b>Ph No.</b>" . htmlspecialchars($rs['contact_no'] ?? '') . "<br>
										</td>
										<td>" . (!empty($rs['paid_date']) ? date("d-M-Y", strtotime($rs['paid_date'])) : 'N/A') . "</td>
										<td style='text-align: left;'>";
                                
                                 $payment_detail = !empty($rs['payment_detail']) ? unserialize($rs['payment_detail']) : [];
									if(is_array($payment_detail)) {
										echo "<b>Payment Type -</b> " . htmlspecialchars($rs['payment_type'] ?? '') . "<br>
											<b>Card holder -</b> " . htmlspecialchars($payment_detail[0] ?? '') . "<br>
											<b>Card Number -</b> " . htmlspecialchars($payment_detail[1] ?? '') . "<br>
											<b>Expiry date -</b> " . htmlspecialchars($payment_detail[2] ?? '') . "<br>";
									}
									
									echo "</td>
										<th style='text-align: right;'>RM" . htmlspecialchars($rs['paid_amt'] ?? '0') . "</th>
										<td>
											<a href='fundcollectionreceipt.php?fund_collection_id=" . intval($rs['fund_collection_id'] ?? 0) . "' class='btn btn-primary' target='_blank'>Print</a>
										</td>
									</tr>";
								}
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>

<script>
function confirmdel() {
    return confirm("Are you sure you want to delete this record?");
}
</script>