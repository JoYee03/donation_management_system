<?php
include("header.php");

$donor_count = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) FROM donor WHERE status='Active'"))[0];
$donation_count = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) FROM item_requests WHERE status='Approved'"))[0];
$completed_count = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) FROM items WHERE status='Claimed'"))[0];
$recipient_count = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) FROM receiver WHERE status='Active'"))[0];
?>
<div id="home-owl" class="owl-carousel owl-theme">
    <!-- First Slide -->
    <div class="home-item">
        <div class="section-bg" style="background-image: url(img/IMG_20191210_133502.jpg);"></div>
        <div class="home">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <div class="home-content">
                            <h1>Donation Management System</h1>
                            <p class="lead">Connecting generosity with need through our digital donation platform.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Slide -->
    <div class="home-item">
        <div class="section-bg" style="background-image: url(img/IMG_20191210_124518.jpg);"></div>
        <div class="home">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <div class="home-content">
                            <h1>Transparent Giving</h1>
                            <p class="lead">Track your donations from donor to recipient with our secure system.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="about" class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="section-title">
                    <h2 class="title">About Our Platform</h2>
                    <p class="sub-title">The Donation Management System is a digital platform designed to streamline charitable giving and resource distribution. Our system connects donors directly with verified needs, ensuring transparency and efficiency in every transaction.</p>
                    <p class="sub-title">Developed as part of our CSE 6234 Software Design project, this platform incorporates modern design patterns and principles to create a reliable, scalable solution for managing donations.</p>
                </div>
            </div>
            <div class="col-md-offset-1 col-md-6">
                <a href="#" class="about-video">
                    <img src="img/charity/IMG_20191210_133502.jpg" style="height: 500px;" alt="Our donation system in action">
                </a>
            </div>
        </div>
    </div>
</div>

<div id="numbers" class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="number">
                    <i class="fa fa-smile-o"></i>
                    <h3 id="donor-count"><?php echo $donor_count; ?></h3>
                    <span>Registered Donors</span>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="number">
                    <i class="fa fa-gift"></i>
                    <h3 id="donation-count"><?php echo $donation_count; ?></h3>
                    <span>Items Donated</span>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="number">
                    <i class="fa fa-check-circle"></i>
                    <h3 id="completed-count"><?php echo $completed_count; ?></h3>
                    <span>Successful Matches</span>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="number">
                    <i class="fa fa-users"></i>
                    <h3 id="recipient-count"><?php echo $recipient_count; ?></h3>
                    <span>Beneficiaries Served</span>
                </div>
            </div>
        </div>
    </div>
</div>

<hr>

<div id="features" class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title text-center">
                    <h2>How Our System Works</h2>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="fa fa-user-plus"></i>
                    <h3>1. Register</h3>
                    <p>Create an account as a donor or recipient with appropriate access levels.</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="fa fa-upload"></i>
                    <h3>2. Post Donations</h3>
                    <p>Donors can easily list items with descriptions, categories and conditions.</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="fa fa-search"></i>
                    <h3>3. Browse & Request</h3>
                    <p>Recipients can search available items and submit requests through the platform.</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="fa fa-check-square"></i>
                    <h3>4. Admin Approval</h3>
                    <p>Our team verifies all donations and requests to ensure quality and appropriateness.</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="fa fa-exchange"></i>
                    <h3>5. Match & Notify</h3>
                    <p>The system automatically notifies parties when matches are made.</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="fa fa-line-chart"></i>
                    <h3>6. Track Impact</h3>
                    <p>View your donation history and see the difference you've made.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="cta" class="section">
    <div class="section-bg" style="background-image: url(img/background-1.jpg);" data-stellar-background-ratio="0.5"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="cta-content text-center">
                    <h1>Join Our Digital Giving Community</h1>
                    <p class="lead">Whether you have items to donate or are in need of assistance, our platform makes the process simple, transparent and efficient. Register today to become part of our mission to connect resources with needs.</p>
                    <a href="" onclick="return false;" data-toggle="modal" data-target="#DonorRegisterModal" class="primary-button">Get Started</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include("footer.php");
?>

<script>
    // Animated counter script
    $(document).ready(function() {
        $('.number h3').each(function() {
            $(this).prop('Counter', 0).animate({
                Counter: $(this).text()
            }, {
                duration: 2000,
                easing: 'swing',
                step: function(now) {
                    $(this).text(Math.ceil(now));
                }
            });
        });
    });
</script>