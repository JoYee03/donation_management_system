<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php
include("header.php");
if(!isset($_SESSION['staff_id']))
{
    echo "<script>window.location='index.php';</script>";
}
if(isset($_GET['delid']))
{
    $sql = "DELETE FROM receiver WHERE receiver_id='$_GET[delid]'";
    $qsql = mysqli_query($con,$sql);
    echo mysqli_error($con);
    if(mysqli_affected_rows($con) == 1)
    {
        echo "<script>alert('Receiver record deleted successfully..');</script>";
        echo "<script>window.location='viewreceiver.php';</script>";
    }
}
?>
</header>
<div id="about" class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <div class="section-title">
            <center><h2 class="title">View Receiver</h2></center>
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

<table id="datatable"  class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Image</th>
            <th>Receiver Type</th>
            <th>Name</th>
            <th>Address</th>
            <th>Documents</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
     $sql = "SELECT receiver.*,receiver_type.receiver_type FROM receiver LEFT JOIN receiver_type on receiver.receiver_type_id=receiver_type.receiver_type_id WHERE (receiver.status='Active' OR receiver.status='Inactive')";
    $qsql = mysqli_query($con,$sql);
    while($rs = mysqli_fetch_array($qsql))
    {
        if($rs['image'] == "")
        {
            $imgname="img/noimage.png";
        }
        else if(file_exists("imgreceiver/".$rs['image']))
        {
            $imgname = "imgreceiver/".$rs['image'];
        }
        else
        {
            $imgname="img/noimage.png";
        }
        echo "<tr>
            <td><img src='$imgname' style='width: 100px; height: 124px;'></td>
            <td>$rs[receiver_type]</td>
            <td>$rs[name]</td>
            <td style='text-align: left;'>$rs[address]<hr><b>Ph. No.</b> $rs[contact_no]</td>
            <td>";
        
        if(file_exists("imgaddressproof/".$rs['address_proof']))
        {
            echo "<a href='imgaddressproof/$rs[address_proof]' class='btn btn-success'  style='width: 125px;' download >Address Proof</a>";
        }
        
        echo "<hr>";
                
        if(file_exists("imgidproof/".$rs['id_proof']))
        {
            echo "<a href='imgidproof/$rs[id_proof]'  class='btn btn-info'  style='width: 125px;' download>ID Proof</a>";
        }
        
        echo "</td>
            <td>$rs[status]</td>
            <td style='width: 55px;'>";
            if($rs['status'] != "Approved")
            {
        echo "<a href='viewreceiver.php?delid=$rs[0]' class='btn btn-danger' onclick='return confirmdel()'  style='width: 75px;'>Delete</a><hr>
                <a href='receiver.php?editid=$rs[0]'  class='btn btn-primary'  style='width: 75px;'>Edit</a>";
        echo "</td>";
            }
        echo "</tr>";
    }
?>
    </tbody>
</table>

    </div>
</div>

</div>

</div>

</div>

<?php
include("footer.php");
?>

<script>
function confirmdel()
{
    if(confirm("Are you sure want to delete this record?") == true)
    {
        return true;
    }
    else
    {
        return false;
    }
}
</script>