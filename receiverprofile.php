<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php
include("header.php");
if(isset($_POST['submit']))
{
    $image = rand() . $_FILES["image"]["name"];
    $id_proof = rand() . $_FILES["id_proof"]["name"];
    $address_proof = rand() . $_FILES["address_proof"]["name"];
    
    move_uploaded_file($_FILES["image"]["tmp_name"],"imgreceiver/".$image);
    move_uploaded_file($_FILES["id_proof"]["tmp_name"],"imgreceiver/".$id_proof);
    move_uploaded_file($_FILES["address_proof"]["tmp_name"],"imgreceiver/".$address_proof);
    
    if(isset($_SESSION['receiver_id']))
    {
        $sql ="UPDATE receiver SET name='$_POST[name]', address='$_POST[address]', contact_no='$_POST[contact_no]', description='$_POST[description]', email_id='$_POST[email_id]'";
        
        if($_FILES["image"]["name"] != "")
        {
            $sql .= ", image='$image'";
        }
        if($_FILES["id_proof"]["name"] != "")
        {
            $sql .= ", id_proof='$id_proof'";
        }
        if($_FILES["address_proof"]["name"] != "")
        {
            $sql .= ", address_proof='$address_proof'";
        }
        
        $sql .= " WHERE receiver_id='$_SESSION[receiver_id]'";
        $qsql = mysqli_query($con,$sql);
        
        if(mysqli_affected_rows($con) == 1)
        {
            echo "<script>alert('Receiver Profile updated successfully..');</script>";
        }
        else
        {
            echo mysqli_error($con);
        }        
    }
}
?>
<?php
if(isset($_SESSION['receiver_id']))
{
    $sqledit = "SELECT * FROM receiver WHERE receiver_id='$_SESSION[receiver_id]'";
    $qsqledit = mysqli_query($con,$sqledit);
    $rsedit = mysqli_fetch_array($qsqledit);
}
?>

<div id="page-header">
    <div class="section-bg" style="background-image: url(img/background-2.jpg);"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="header-content">
                    <h1>Receiver Profile</h1>
                </div>
            </div>
        </div>
    </div>
</div>
</header>

<div class="section" style="padding-top: 1px;">
    <div class="container">
        <div class="row">
            <main id="" class="col-md-12">
                <div class="">
                    <div class="">
                        <div class="article-comments">
                            <div class="media">
                                <div class="media-left">
                                    <?php
                                    if($rsedit['image'] == "")
                                    {
                                        echo "<img class='media-object' src='img/no-image-icon.png' style='width: 100px;height: 100px;'>";
                                    }
                                    else if(file_exists("imgreceiver/".$rsedit['image']))
                                    {
                                        echo "<img class='media-object' src='imgreceiver/".$rsedit['image']. "' style='width: 100px;height: 100px;'>";
                                    }
                                    else
                                    {
                                        echo "<img class='media-object' src='img/no-image-icon.png' style='width: 100px;height: 100px;'>";    
                                    }
                                    ?>
                                </div>
                                <div class="media-body">
                                    <p>
                                        <form method="post" action="" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-2" style="padding-top: 5px;">Name</div>
                                                <div class="col-md-10">
                                                    <input type="text" name="name" id="name" class="form-control" value="<?php echo $rsedit['name']; ?>">
                                                </div>
                                            </div>

                                            <br>

                                            <div class="row">
                                                <div class="col-md-2" style="padding-top: 5px;">Profile Image</div>
                                                <div class="col-md-10">
                                                    <input type="file" name="image" id="image" class="form-control">
                                                    <?php
                                                    if($rsedit['image'] == "")
                                                    {
                                                        echo "<img src='img/no-image-icon.png' style='height: 300px;'>";
                                                    }
                                                    else if(file_exists("imgreceiver/".$rsedit['image']))
                                                    {
                                                        echo "<img src='imgreceiver/".$rsedit['image']. "' style='height: 250px;'>";
                                                    }
                                                    else
                                                    {
                                                        echo "<img src='img/no-image-icon.png' style='height: 300px;'>";    
                                                    }
                                                    ?>
                                                </div>
                                            </div>

                                            <br>

                                            <div class="row">
                                                <div class="col-md-2" style="padding-top: 5px;">Address</div>
                                                <div class="col-md-10">
                                                    <textarea name="address" id="address" class="form-control"><?php echo $rsedit['address']; ?></textarea>
                                                </div>
                                            </div>

                                            <br>

                                            <div class="row">
                                                <div class="col-md-2" style="padding-top: 5px;">Contact Number</div>
                                                <div class="col-md-10">
                                                    <input type="text" name="contact_no" id="contact_no" class="form-control" value="<?php echo $rsedit['contact_no']; ?>">
                                                </div>
                                            </div>

                                            <br>

                                            <div class="row">
                                                <div class="col-md-2" style="padding-top: 5px;">Email ID</div>
                                                <div class="col-md-10">
                                                    <input type="email" name="email_id" id="email_id" class="form-control" value="<?php echo $rsedit['email_id']; ?>">
                                                </div>
                                            </div>

                                            <br>

                                            <div class="row">
                                                <div class="col-md-2" style="padding-top: 5px;">Description</div>
                                                <div class="col-md-10">
                                                    <textarea name="description" id="description" class="form-control"><?php echo $rsedit['description']; ?></textarea>
                                                </div>
                                            </div>

                                            <br>

                                            <div class="row">
                                                <div class="col-md-2" style="padding-top: 5px;">ID Proof</div>
                                                <div class="col-md-10">
                                                    <input type="file" name="id_proof" id="id_proof" class="form-control">
                                                    <?php
                                                    if($rsedit['id_proof'] != "" && file_exists("imgreceiver/".$rsedit['id_proof']))
                                                    {
                                                        echo "<a href='imgreceiver/".$rsedit['id_proof']."' target='_blank'>View Current ID Proof</a>";
                                                    }
                                                    ?>
                                                </div>
                                            </div>

                                            <br>

                                            <div class="row">
                                                <div class="col-md-2" style="padding-top: 5px;">Address Proof</div>
                                                <div class="col-md-10">
                                                    <input type="file" name="address_proof" id="address_proof" class="form-control">
                                                    <?php
                                                    if($rsedit['address_proof'] != "" && file_exists("imgreceiver/".$rsedit['address_proof']))
                                                    {
                                                        echo "<a href='imgreceiver/".$rsedit['address_proof']."' target='_blank'>View Current Address Proof</a>";
                                                    }
                                                    ?>
                                                </div>
                                            </div>

                                            <br>

                                            <div class="row">
                                                <div class="col-md-2" style="padding-top: 5px;"></div>
                                                <div class="col-md-10">
                                                    <input type="submit" name="submit" id="submit" class="form-control btn btn-success" style="width: 200px;">
                                                </div>
                                            </div>
                                        </form>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
<?php
include("footer.php");
?>