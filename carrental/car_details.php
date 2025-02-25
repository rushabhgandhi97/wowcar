<?php 
session_start();
include('includes/config.php');
error_reporting(0);
if(isset($_POST['submit']))
{
  $fromdate=$_POST['fromdate'];
  $todate=$_POST['todate']; 
  $message=$_POST['message'];
  $useremail=$_SESSION['login'];
  $status=0;
  $vhid=$_GET['vhid'];
  $bookingno=mt_rand(100000000, 999999999);
  $ret="SELECT * FROM wowbooking where (:fromdate BETWEEN date(FromDate) and date(ToDate) || :todate BETWEEN date(FromDate) and date(ToDate) || date(FromDate) BETWEEN :fromdate and :todate) and VehicleId=:vhid";
  $query1 = $dbh -> prepare($ret);
  $query1->bindParam(':vhid',$vhid, PDO::PARAM_STR);
  $query1->bindParam(':fromdate',$fromdate,PDO::PARAM_STR);
  $query1->bindParam(':todate',$todate,PDO::PARAM_STR);
  $query1->execute();
  $results1=$query1->fetchAll(PDO::FETCH_OBJ);

  if($query1->rowCount()==0)
  {

    $sql="INSERT INTO  wowbooking(userEmail,VehicleId,FromDate,ToDate,pick_id,drop_id,message,Status,BookingNumber) VALUES(:useremail,:vhid,:fromdate,:todate,:pick_id,:drop_id,:message,:status,:bookingno)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':useremail',$useremail,PDO::PARAM_STR);
    $query->bindParam(':vhid',$vhid,PDO::PARAM_STR);
    $query->bindParam(':fromdate',$fromdate,PDO::PARAM_STR);
    $query->bindParam(':todate',$todate,PDO::PARAM_STR);
    $query->bindParam(':pick_id',$pick_id,PDO::PARAM_STR);
    $query->bindParam(':drop_id',$drop_id,PDO::PARAM_STR);
    $query->bindParam(':message',$message,PDO::PARAM_STR);
    $query->bindParam(':status',$status,PDO::PARAM_STR);
    $query->bindParam(':bookingno',$bookingno,PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();
    if($lastInsertId)
    {
      echo "<script>alert('Booked successfuly.');</script>";
      echo "<script type='text/javascript'> document.location = 'my_booking.php'; </script>";
    }
    else 
    {
      echo "<script>alert('Something went wrong. Please try again');</script>";
      echo "<script type='text/javascript'> document.location = 'car_list.php'; </script>";
    } 
  }  else{
   echo "<script>alert('Car already booked for these days');</script>"; 
   echo "<script type='text/javascript'> document.location = 'car_list.php'; </script>";
 }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Car Rental|Car Details</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">
  <meta content="Author" name="WebThemez">
  <!-- Favicons -->
  <link href="img/favicon.png" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800|Montserrat:300,400,700" rel="stylesheet">

  <!-- Bootstrap CSS File -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Libraries CSS Files -->
  <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="lib/magnific-popup/magnific-popup.css" rel="stylesheet">
  <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">

  <!-- Main Stylesheet File -->
  <link href="css/style.css" rel="stylesheet"> 
</head>

<body id="body"> 
 <?php include('includes/header.php');?>
 <section id="innerBanner"> 
  <div class="inner-content">
    <h2><span>ABOUT CAR</span><br>We provide high quality and well serviced cars </h2>
    <div> 
    </div>
  </div> 
</section><!-- #Page Banner -->

<main id="main">
    <!--==========================
      Clients Section
      ============================-->
      <section id="clients"  class="wow fadeInUp">
        <div class="container">
          <div class="section-header">
            <h2>Car details</h2>
            <p>Luxurious, Spacious and smooth sailing car with latest features like Air conditioning, Cruise control, Power windows, Disc brakes and leather seats that provide great comfort to you! It gives great mileage and can go from 0-100 in 10 seconds.</p>
          </div>
          <?php 
          $vhid=intval($_GET['vhid']);
          $sql = "SELECT wowvehicles.*,wowbrands.BrandName,wowbrands.id as bid  from wowvehicles join wowbrands on wowbrands.id=wowvehicles.VehiclesBrand where wowvehicles.id=:vhid";
          $query = $dbh -> prepare($sql);
          $query->bindParam(':vhid',$vhid, PDO::PARAM_STR);
          $query->execute();
          $results=$query->fetchAll(PDO::FETCH_OBJ);
          $cnt=1;
          if($query->rowCount() > 0)
          {
            foreach($results as $result)
            {  
              $_SESSION['brndid']=$result->bid;  
              ?>  
              <div class="owl-carousel clients-carousel">
                <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" alt="" style="height: 150px; width:300px;">
                <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage2);?>" alt="" style="height: 150px; width: 300px;">
                <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage3);?>" alt="" style="height: 150px; width: 300px;">
                <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage4);?>" alt="" style="height: 150px; width: 300px;">
              </div>
            </div>
          </section><!-- #clients -->

          <!--Listing-detail-->
          <section class="listing-detail">
            <div class="container">
              <div class="listing_detail_head row">
                <div class="col-md-9">
                  <h2><?php echo htmlentities($result->BrandName);?> , <?php echo htmlentities($result->VehiclesTitle);?></h2>
                </div>
                <div class="col-md-3">
                  <div class="price_info">
                    <p>$<?php echo htmlentities($result->PricePerDay);?> </p>Per Day

                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-9">
                  <div class="main_features">
                    <ul>

                      <li> <i class="fa fa-calendar" aria-hidden="true"></i>
                        <h5><?php echo htmlentities($result->ModelYear);?></h5>
                        <p>Reg.Year</p>
                      </li>
                      <li> <i class="fa fa-cogs" aria-hidden="true"></i>
                        <h5><?php echo htmlentities($result->FuelType);?></h5>
                        <p>Fuel Type</p>
                      </li>

                      <li> <i class="fa fa-user-plus" aria-hidden="true"></i>
                        <h5><?php echo htmlentities($result->SeatingCapacity);?></h5>
                        <p>Seats</p>
                      </li>
                      <li> <i class="fa fa-tachometer" aria-hidden="true"></i>
                        <h5><?php echo htmlentities($result->veh_odo_lim);?></h5>
                        <p>miles/day</p>
                      </li>
                      <li> <i class="fa fa-usd" aria-hidden="true"></i>
                        <h5><?php echo htmlentities($result->veh_om_fees);?></h5>
                        <p>om fees/mile</p>
                      </li>
                    </ul>
                  </div>
                  <div class="listing_more_info">
                    <div class="listing_detail_wrap"> 
                      <!-- Nav tabs -->
                      <ul class="nav nav-tabs gray-bg" role="tablist">
                        <li role="presentation" class="active"><a href="#vehicle-overview " aria-controls="vehicle-overview" role="tab" style="background-color: black;" data-toggle="tab">Vehicle Overview </a></li>

                        <li role="presentation"><a href="#accessories" aria-controls="accessories" role="tab" data-toggle="tab">Accessories</a></li>
                      </ul>

                      <!-- Tab panes -->
                      <div class="tab-content"> 
                        <!-- vehicle-overview -->
                        <div role="tabpanel" class="tab-pane active" id="vehicle-overview">

                          <p><?php echo htmlentities($result->VehiclesOverview);?></p>
                        </div>


                        <!-- Accessories -->
                        <div role="tabpanel" class="tab-pane" id="accessories"> 
                          <!--Accessories-->
                          <table>
                            <thead>
                              <tr>
                                <th colspan="2">Accessories</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>Air Conditioner</td>
                                <?php if($result->AirConditioner==1)
                                {
                                  ?>
                                  <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                  <?php 
                                } else { ?> 
                                 <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                 <?php 
                               } ?> </tr>

                               <tr>
                                <td>AntiLock Braking System</td>
                                <?php if($result->AntiLockBrakingSystem==1)
                                {
                                  ?>
                                  <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                <?php } else {?>
                                  <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                <?php } ?>
                              </tr>

                              <tr>
                                <td>Power Steering</td>
                                <?php if($result->PowerSteering==1)
                                {
                                  ?>
                                  <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                <?php } else { ?>
                                  <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                <?php } ?>
                              </tr>


                              <tr>

                                <td>Power Windows</td>

                                <?php if($result->PowerWindows==1)
                                {
                                  ?>
                                  <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                <?php } else { ?>
                                  <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                <?php } ?>
                              </tr>

                              <tr>
                                <td>CD Player</td>
                                <?php if($result->CDPlayer==1)
                                {
                                  ?>
                                  <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                <?php } else { ?>
                                  <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                <?php } ?>
                              </tr>

                              <tr>
                                <td>Leather Seats</td>
                                <?php if($result->LeatherSeats==1)
                                {
                                  ?>
                                  <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                <?php } else { ?>
                                  <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                <?php } ?>
                              </tr>

                              <tr>
                                <td>Central Locking</td>
                                <?php if($result->CentralLocking==1)
                                {
                                  ?>
                                  <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                <?php } else { ?>
                                  <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                <?php } ?>
                              </tr>

                              <tr>
                                <td>Power Door Locks</td>
                                <?php if($result->PowerDoorLocks==1)
                                {
                                  ?>
                                  <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                <?php } else { ?>
                                  <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                <?php } ?>
                              </tr>
                              <tr>
                                <td>Brake Assist</td>
                                <?php if($result->BrakeAssist==1)
                                {
                                  ?>
                                  <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                <?php  } else { ?>
                                  <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                <?php } ?>
                              </tr>

                              <tr>
                                <td>Driver Airbag</td>
                                <?php if($result->DriverAirbag==1)
                                {
                                  ?>
                                  <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                <?php } else { ?>
                                  <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                <?php } ?>
                              </tr>

                              <tr>
                               <td>Passenger Airbag</td>
                               <?php if($result->PassengerAirbag==1)
                               {
                                ?>
                                <td><i class="fa fa-check" aria-hidden="true"></i></td>
                              <?php } else {?>
                                <td><i class="fa fa-close" aria-hidden="true"></i></td>
                              <?php } ?>
                            </tr>

                            <tr>
                              <td>Crash Sensor</td>
                              <?php if($result->CrashSensor==1)
                              {
                                ?>
                                <td><i class="fa fa-check" aria-hidden="true"></i></td>
                                <?php 
                              } else { ?>
                                <td><i class="fa fa-close" aria-hidden="true"></i></td>
                                <?php
                              } ?>
                            </tr>

                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>

                </div>
                <?php 
              }
            } ?>

          </div>

          <!--Side-Bar-->
          <aside class="col-md-9">

            <div class="share_vehicle">
              <p>Share: <a href="#"><i class="fa fa-facebook-square" aria-hidden="true"></i></a> <a href="#"><i class="fa fa-twitter-square" aria-hidden="true"></i></a> <a href="#"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a> <a href="#"><i class="fa fa-google-plus-square" aria-hidden="true"></i></a> </p>
            </div>
            <div class="sidebar_widget">
              <div class="widget_heading">
                <h5><i class="fa fa-envelope" aria-hidden="true"></i>Book Now</h5>
              </div>
              <form method="post">
                <div class="form-group">
                  <label>From Date:</label>
                  <input type="date" class="form-control" name="fromdate" placeholder="From Date" required>
                </div>
                <div class="form-group">
                  <label>To Date:</label>
                  <input type="date" class="form-control" name="todate" placeholder="To Date" required>
                </div>
                <div class="form-group">
                  <textarea rows="4" class="form-control" name="message" placeholder="Message" required></textarea>
                </div> 


                <?php
                    $mysqli = NEW mysqli("localhost","root","",'wowcar');
                    $pickup = $mysqli->query("SELECT City,State FROM wowlocation");
                ?>

                <label for="pickup">Pick-up Location:</label>
                <select name="pickup" class="form-control" required>
                <option value="">--Choose a Pickup Location--</option>
                  <?php 
                  while($rows = $pickup->fetch_assoc())
                  {
                    $City = $rows['City'];
                    $State = $rows['State'];
                    echo "<option value = '$City , $State'>$City,$State</option>";
                  }
                  ?>
                </select>

                <?php
                    $mysqli = NEW mysqli("localhost","root","",'wowcar');
                    $drop = $mysqli->query("SELECT City,State FROM wowlocation");
                ?>

                <label for="dropoff">Drop-Off Location:</label>
                <select name="dropoff" class="form-control" required>
                <option value="">--Choose a Dropoff Location--</option>
                  <?php 
                  while($rows = $drop->fetch_assoc())
                  {
                    $City = $rows['City'];
                    $State = $rows['State'];
                    echo "<option value = '$City , $State'>$City,$State</option>";
                  }
                  ?>
                </select>
                </br></br>
                <div class="coupon">
                  <div class="coupponcontainer">
                    <h3>Special Offer</h3>
                  </div>
                  <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" alt="Avatar" style="height: 150px; width:400px;">
                  <div class="container" style="background-color:white">
                    <h6><b>20% OFF YOUR PURCHASE</b></h6>
                  </div>
                  <div class="coupponcontainer">
                    <p>Use Promo Code: <span class="promo">WOW2020</span></p>
                    <p class="expire">Expires: May 17, 2021</p>
                  </div>
                </div>

                <input type="text" id="discount" name="discount" placeholder="Type discount coupon code" style="width:20%">
                <input type="submit" class="btn" style="background-color: orange;"  name="apply" value="Apply"></br>

                <div class="col-50">
                <div class="container">
                <div class="listing_detail_head row">
                  <div class="col-md-9">
                  <h6>Payment</h6>
                  </div>
                  <div class="col-md-3">
                    <div class="price_info">
                      <p>$<?php echo htmlentities($result->PricePerDay);?> </p>Per Day

                    </div>
                  </div>
                </div>
                  </div>
                  <label for="fname">Accepted Cards</label>
                <div class="icon-container">
                  <i class="fa fa-cc-visa" style="color:navy;"></i>
                  <i class="fa fa-cc-amex" style="color:blue;"></i>
                  <i class="fa fa-cc-mastercard" style="color:red;"></i>
                  <i class="fa fa-cc-discover" style="color:orange;"></i>
                </div>
                <label for="cname">Name on Card</label>
                  <input type="text" id="cname" name="cardname" placeholder="John More Doe">
                  <label for="ccnum"> <i class="fa fa-credit-card"></i>Credit card number</label>
                  <input type="text" id="ccnum" name="cardnumber" placeholder="1111-2222-3333-4444">
                  <label for="expmonth"> <i class="fa fa-calendar"></i>Exp Month</label>
                  <input type="text" id="expmonth" name="expmonth" placeholder="September"></br>
                  <label for="expyear"><i class="fa fa-calendar"></i>Exp Year</label>
                  <input type="text" id="expyear" name="expyear" placeholder="2018"></br>
                  <label for="cvv"><i class="fa fa-credit-card-alt"></i>CVV</label>
                  <input type="password" id="cvv" name="cvv" placeholder="352"></br></br>
                
              </div>

              <div class="row">
                <div class="col-50">
                  <h3>Billing Address</h3>
                  <label for="fname"><i class="fa fa-user"></i> Full Name</label>
                  <input type="text" id="fname" name="firstname" placeholder="John M. Doe">
                  <label for="email"><i class="fa fa-envelope"></i> Email</label>
                  <input type="text" id="email" name="email" placeholder="john@example.com">
                  <label for="adr"><i class="fa fa-address-card-o"></i> Address</label>
                  <input type="text" id="adr" name="address" placeholder="542 W. 15th Street">
                  <label for="city"><i class="fa fa-institution"></i> City</label>
                  <input type="text" id="city" name="city" placeholder="New York">
                  <label for="state">State</label>
                  <input type="text" id="state" name="state" placeholder="NY">
                  <label for="zip">Zip</label>
                  <input type="text" id="zip" name="zip" placeholder="10001">
                </div>
              </div>
      
                <?php if($_SESSION['login'])
                {?>
                  <div class="form-group">
                    <input type="submit" class="btn" style="background-color: orange;"  name="submit" value="Book Now">
                  </div>
                  <?php 
                } else { ?>
                  <a href="#loginform" class="btn btn-xs uppercase" data-toggle="modal" data-dismiss="modal" style="background-color: #49a3ff;">Login For Book</a>

                  <?php 
                } ?>
              </form>
            </div>
          </aside>
          <!--/Side-Bar--> 
        </div>

        <div class="space-20"></div>
        <div class="divider"></div>

        <!--Similar-Cars-->
        <div class="similar_cars">
          <h3>Similar Cars</h3>
          <div class="row">
            <?php 
            $bid=$_SESSION['brndid'];
            $sql="SELECT wowvehicles.VehiclesTitle,wowbrands.BrandName,wowvehicles.PricePerDay,wowvehicles.FuelType,wowvehicles.ModelYear,wowvehicles.veh_odo_lim,wowvehicles.veh_om_fees,wowvehicles.id,wowvehicles.SeatingCapacity,wowvehicles.VehiclesOverview,wowvehicles.Vimage1 from wowvehicles join wowbrands on wowbrands.id=wowvehicles.VehiclesBrand where wowvehicles.VehiclesBrand=:bid";
            $query = $dbh -> prepare($sql);
            $query->bindParam(':bid',$bid, PDO::PARAM_STR);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            $cnt=1;
            if($query->rowCount() > 0)
            {
              foreach($results as $result)
              { 
                ?>      
                <div class="col-md-3 grid_listing">
                  <div class="product-listing-m gray-bg">
                    <div class="product-listing-img"> <a href="car_details.php?vhid=<?php echo htmlentities($result->id);?>"><img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" class="img-responsive" style="height: 200px; width: 360px;" alt="image" /> </a>
                    </div>
                    <div class="product-listing-content">
                      <h5><a href="car_details.php?vhid=<?php echo htmlentities($result->id);?>"><?php echo htmlentities($result->BrandName);?> , <?php echo htmlentities($result->VehiclesTitle);?></a></h5>
                      <p class="list-price">$<?php echo htmlentities($result->PricePerDay);?></p>

                      <ul class="features_list">

                       <li><i class="fa fa-user" aria-hidden="true"></i><?php echo htmlentities($result->SeatingCapacity);?> seats</li>
                       <li><i class="fa fa-calendar" aria-hidden="true"></i><?php echo htmlentities($result->ModelYear);?> model</li>
                       <li><i class="fa fa-car" aria-hidden="true"></i><?php echo htmlentities($result->FuelType);?></li>
                        <li><i class="fa fa-tachometer" aria-hidden="true"></i><?php echo htmlentities($result->veh_odo_lim);?> miles/day</li>
                        <li><i class="fa fa-usd" aria-hidden="true"></i><?php echo htmlentities($result->veh_om_fees);?> om fees/mile</li>
                     </ul>
                   </div>
                 </div>
               </div>
               <?php 
             }
           } ?>       

         </div>
       </div>
       <!--/Similar-Cars--> 

     </div>
   </section>
   <!--/Listing-detail--> 

    <!--==========================
      Call To Action Section
      ============================-->
      <section id="call-to-action" class="wow fadeInUp">
        <div class="container">
          <div class="row">
            <div class="col-lg-9 text-center text-lg-left">
            <h3 class="cta-title">WOW services</h3>
             <p class="cta-text">Wow has various classes of vehicle such as small car, mid-size car, luxury car, SUV, Premium SUV, MiniVan, and Station Wagon etc.
             At present, WOW does not provide vehicle insurance to their customers for car rental service and customers need to bring his/her own insurance
             </p>            </div>
            <div class="col-lg-3 cta-btn-container text-center">
              <a class="cta-btn align-middle" href="contact.php">Contact Us</a>
            </div>
          </div>

        </div>
      </section><!-- #call-to-action -->




    </main>

  <!--==========================
    Footer
    ============================-->
    <?php include('includes/footer.php');?><!-- #footer -->

    <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

    <!--Login-Form -->
    <?php include('includes/login.php');?>
    <!--/Login-Form --> 

    <!--Register-Form -->
    <?php include('includes/registration.php');?>

    <!--/Register-Form --> 

    <!--Forgot-password-Form -->
    <?php include('includes/forgotpassword.php');?>

    <!-- JavaScript  -->
    <script src="lib/jquery/jquery.min.js"></script>
    <script src="lib/jquery/jquery-migrate.min.js"></script>
    <script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/superfish/hoverIntent.js"></script>
    <script src="lib/superfish/superfish.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/magnific-popup/magnific-popup.min.js"></script>
    <script src="lib/sticky/sticky.js"></script> 
    <script src="contact/jqBootstrapValidation.js"></script>
    <script src="contact/contact_me.js"></script>
    <script src="js/main.js"></script>

  </body>
  </html>