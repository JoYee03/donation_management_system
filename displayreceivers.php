<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("header.php");
include("dbconnection.php"); // Make sure you include your database connection
?>

</header>

<div id="cta" class="section">
    <div class="section-bg" style="background-image: url(img/background-1.jpg);" data-stellar-background-ratio="0.5"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="cta-content text-center">
                    <h1>Receivers</h1>
                    <p class="lead">View all registered receivers in our system</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="blog" class="section">
    <div class="container">
        <div class="row">
            <?php
            // Modified query to handle cases where receiver_type might not exist
            $sqlreceiver = "SELECT receiver.* FROM receiver WHERE receiver.status='Active'";
            $qsqlreceiver = mysqli_query($con,$sqlreceiver);
            
            if(!$qsqlreceiver) {
                die("Database error: " . mysqli_error($con));
            }
            
            while($rsreceiver = mysqli_fetch_array($qsqlreceiver))
            {
                // Get receiver type if available
                $receiver_type = "General Receiver"; // Default value
                if(isset($rsreceiver['receiver_type_id'])) {
                    $type_query = mysqli_query($con, "SELECT receiver_type FROM receiver_type WHERE receiver_type_id = ".$rsreceiver['receiver_type_id']);
                    if($type_query && mysqli_num_rows($type_query) > 0) {
                        $type_data = mysqli_fetch_assoc($type_query);
                        $receiver_type = $type_data['receiver_type'];
                    }
                }
                
                if($rsreceiver['image'] == "")
                {
                    $imgname="img/noimage.png";
                }
                else if(file_exists("imgreceiver/".$rsreceiver['image']))
                {
                    $imgname = "imgreceiver/".$rsreceiver['image'];
                }
                else
                {
                    $imgname="img/noimage.png";
                }
            ?>
            <div class="col-md-4">
                <div class="article">
                    <div class="article-img">
                        <img src='<?php echo $imgname; ?>' style="height: 350px;" >
                        </a>
                    </div>
                    <div class="article-content">
                        <h3 class="article-title">
                                <?php echo $rsreceiver['name']; ?>
                            </a>
                            <span style="font-size: 15px;"> (<?php echo $receiver_type; ?>)</span>
                        </h3>
                        <p><?php echo $rsreceiver['description'] ?? 'Registered receiver in our system'; ?></p>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>

<?php
include("footer.php");
?>