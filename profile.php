<?php include('header.php');
if(!isset($_SESSION['user']))
{
	header('location:login.php');
}
	$qry2=mysqli_query($con,"select * from tbl_movie where movie_id='".$_SESSION['movie']."'");
	$movie=mysqli_fetch_array($qry2);
	?>
<div class="content">
	<div class="wrap">
		<div class="content-top">
				<div class="section group">
					<div class="about span_1_of_2">	
						<h3 style="color:black;" class="text-center">BOOKING HISTORY</h3>
						<button class="download-invoice">download</button>
						<?php include('msgbox.php');?>
						<?php
				$bk=mysqli_query($con,"select * from tbl_bookings where user_id='".$_SESSION['user']."'");
				if(mysqli_num_rows($bk))
				{
					?>
					<table class="table table-bordered">
						<thead>
						<th>Booking Id</th>
						<th>Movie</th>
						<th>Theatre</th>
						<th>Screen</th>
						<th>Show</th>
						<th>Seats</th>
						<th>Amount</th>
						<th></th>
						</thead>
						<tbody>
						<?php
						while($bkg=mysqli_fetch_array($bk))
						{
							$m=mysqli_query($con,"select * from tbl_movie where movie_id=(select movie_id from tbl_shows where s_id='".$bkg['show_id']."')");
							$mov=mysqli_fetch_array($m);
							$s=mysqli_query($con,"select * from tbl_screens where screen_id='".$bkg['screen_id']."'");
							$srn=mysqli_fetch_array($s);
							$tt=mysqli_query($con,"select * from tbl_theatre where id='".$bkg['t_id']."'");
							$thr=mysqli_fetch_array($tt);
							$st=mysqli_query($con,"select * from tbl_show_time where st_id=(select st_id from tbl_shows where s_id='".$bkg['show_id']."')");
							$stm=mysqli_fetch_array($st);
							?>
							<tr>
								<td>
									<?php echo $bkg['ticket_id'];?>
								</td>
								<td>
									<?php echo $mov['movie_name'];?>
								</td>
								<td>
									<?php echo $thr['name'];?>
								</td>
								<td>
									<?php echo $srn['screen_name'];?>
								</td>
								<td>
									<?php echo $stm['name'];?>
								</td>
								<td>
									<?php echo $bkg['no_seats'];?>
								</td>
								<td>
									Rs. <?php echo $bkg['amount'];?>
								</td>
								<td>
									<?php  if($bkg['ticket_date']<date('Y-m-d'))
									{
										?>
										<i class="glyphicon glyphicon-ok"></i>
										<?php
									}
									else
									{?>
									<a href="cancel.php?id=<?php echo $bkg['book_id'];?>" style="text-decoration:none; color:red;">Cancel</a>
									<?php
									}
									?>
								</td>
							</tr>
							<?php
						}
						?></tbody>
					</table>

					<?php
				}
				else
				{
					?>
					<h3 style="color:red;" class="text-center">No Previous Bookings Found!</h3>
					<p>Once you start booking movie tickets with this account, you'll be able to see all the booking history.</p>
					<?php
				}
				?>
					</div>			
				<?php include('movie_sidebar.php');?>
				
			</div>
				<div class="clear"></div>		
			</div>
	</div>
</div>
<?php include('footer.php');?>

<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/html2pdf.bundle.js"></script>
<script type="text/javascript">
	$('#seats').change(function(){
		var charge= 300;
		amount=charge*$(this).val();
		$('#amount').html("Rs "+amount);
		$('#hm').val(amount);
	});

	// let user = details.data;
	// let user_name = user.name;
	// let user_address = user.address + "<br> " + user.city + " - " + user.pincode + ",<br> " + user.state;
	// let user_mobile = user.phonenumber;
	// let course_name = user.course_name;
	// course_id = user.course_id;
	// let price = user.course_price;
	// let course_price = Math.floor(price / 1.18);
	// let gst = price - course_price;
	let date_now = new Date();
	let timestamp = new Date().getTime();
	// console.log(timestamp);
	// show details on page
	$(".user").html("test");
	$(".course-name").html("test");
	// add date
	let date = new Date(date_now).getDate();
	let month = new Date(date_now).getMonth();
	let year = new Date(date_now).getFullYear();
	let newDate = date < 10 ? "0" + date : date;
	let monthArray = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
	let newMonth = monthArray[month];
	let invoice_date = newDate + " " + newMonth + " " + year;
	let template =  `<table style="width: 100%; margin: 0 auto;font-size: 15px;">
                                    <tr>
                                        <td>
                                            <img src="./icons/header.png" alt="header" width="100%">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="d-flex align-items-center justify-content-between"
                                            style=" border-bottom: 2px solid #140E38; margin-top: 30px;padding-bottom: 20px;">
                                            <img src="./icons/finstreet-black.png" alt="logo" width="125">
                                            <div class="d-flex flex-column align-items-end">
                                                <span>GSTIN: 03AAAFQ8372P1ZS</span>
                                                <a href="https://www.finstreet.in"
                                                    style="display: block; color: black; text-decoration: none;">www.<span
                                                        style="color: #140E38;">finstreet</span>.in</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="margin: 30px 0;" class="d-flex align-items-center justify-content-between">
                                            <h1><b>INVOICE</b></h1>
                                            <div class="d-flex align-items-end flex-column">
                                                <span>Invoice Date: &nbsp;<span class="invoice-date">${invoice_date}</span></span>
                                                <b>Invoice No: <span class="invoice-no">${timestamp}</span></b>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="d-flex justify-content-between mb-4">
                                            <div>
                                                <b style="line-height: 30px;">Bill to</b>
                                                <div style="line-height: 21px;max-width: 280px;min-height: 135px;">
                                                    <span class="customer_name">${"user_name"}</span>,<br>
                                                    <span class="customer_address">${"user_address"}</span><br>
                                                    <span class="customer_mobile">${"user_mobile"}</span><br>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <b style="line-height: 30px;">Ship to</b>
                                                <div style="line-height: 21px;max-width: 280px;min-height: 135px;">
                                                    <span class="customer_name">${"user_name"}</span>,<br>
                                                    <span class="customer_address">${"user_address"}</span><br>
                                                    <span class="customer_mobile">${"user_mobile"}</span><br>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>GSTIN :</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table border="0" style="color: white; width: 100%; text-align: center;margin-top: 24px;">
                                                <thead style="background-color: #194089;">
                                                    <td style="width: 10%; padding: 6px 0; border: 1px solid black; border-bottom: 1px;">
                                                        S.No
                                                    </td>
                                                    <td
                                                        style="width: 40%; padding: 6px 0; border: 1px solid black; border-bottom: 1px; border-left: 1px;">
                                                        ITEM
                                                        DESCRIPTION</td>
                                                    <td
                                                        style="width: 15%; padding: 6px 0; border: 1px solid black; border-bottom: 1px; border-left: 1px;">
                                                        MRP
                                                    </td>
                                                    <td
                                                        style="width: 15%; padding: 6px 0; border: 1px solid black; border-bottom: 1px; border-left: 1px;">
                                                        QUANTITY</td>
                                                    <td
                                                        style="width: 20%; padding: 6px 0; border: 1px solid black; border-bottom: 1px; border-left: 1px;">
                                                        AMOUNT
                                                    </td>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td
                                            style="color: black;font-size: 14px; border-top: 2px solid #140E38; padding-top: 24px; display: grid;margin: 120px 0 20px 0; grid-template-columns: repeat(3,1fr);justify-items: center;">
                                            <div class="d-flex align-items-center">
                                                <img style="margin-right: 10px;" src="./icons/mobile.png" alt="mobile">
                                                <span>+91 7717303384</span>
                                            </div>
                                            <div class="d-flex align-items-center" style="min-width: 300px;">
                                                <img style="margin-right: 10px;" src="./icons/address.png" alt="address">
                                                <address>
                                                    Next57 Coworking,
                                                    Plot No. 57, Industrial Area Phase I,
                                                    Chandigarh, 160002
                                                </address>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <img style="margin-right: 10px;" src="./icons/email.png" alt="email">
                                                <a style="display: block; color: black; text-decoration: none;"
                                                    href="mailto:events@finstreet.in">events@finstreet.in</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img src="./icons/footer.png" alt="footer" width="100%">
                                        </td>
                                    </tr>
                                </table>`;



								// pdf download code start
		$('.download-invoice').on("click", () => {
			console.log("clicked");
            const invoice = template;
            var opt = {
                margin: .25,
                filename: 'invoice.pdf',
                image: { type: 'jpeg', quality: 1 },
                html2canvas: { dpi: 192, scale: 4, letterRendering: true, useCORS: true },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            html2pdf().from(invoice).set(opt).save();
        });
</script>