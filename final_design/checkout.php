<?php
	if(!session_id()){
		session_start();
	}
	if(isset($_SESSION['skinbae_user'])){
		$user_id = base64_decode(base64_decode(base64_decode(base64_decode(addslashes($_SESSION['skinbae_user'])))));
		require_once("includes/header.php");
		$get_user = $obj->select("*","user_master","user_status='1' AND user_id='$user_id'");
		if(is_array($get_user)){
		?>
		<style type="text/css">
			.select_qty .btn-number{
			background: #f9f9f9!important;
			border: none!important;
			font-weight: 100!important;
			color: #bd8a66!important;
			}
			.myqty{
			background: #fff!important;
			margin-top: 2px;
			padding-top: 15px;
			}
			.select_qty .input-group{
			width: 122px;
			}
			.form-group {
			margin-bottom: 13.3px;
			}
		</style>
		<section class="section banner nopadbot" style="background-image:url('upload/parallax_02.jpg');" data-img-width="2000" data-img-height="14998" data-diff="100">
			<div class="overlay"></div>
			<div class="container">
				<div class="page-header">
					<div class="bread">
						<ol class="breadcrumb">
							<li><a href="#">Home</a></li>
							<li class="active">Checkout</li>
						</ol>
					</div><!-- end bread -->
					<h1>Checkout</h1>
				</div>
			</div>
		</section>
		
		<section class="section border-top">
			<div class="container">
				<div class="row">
					<div class="col-md-6 ">
						<div class="row cart-body">
							<form class="form-horizontal" method="post">
								<div class="col-lg-12 col-md-12">
									<!--REVIEW ORDER-->
									<div class="panel panel-info">
										<div class="panel-heading">
											Review Order
										</div>
										<div class="panel-body" id="cart_body">
											<?php
												$total_cart = 0;
												$get_cart = $obj->select("P.*, CA.*, M.user_id","user_cart AS CA INNER JOIN user_master AS M ON CA.cart_user_id=M.user_id INNER JOIN products AS P ON P.product_id=CA.cart_product_id","CA.cart_status='1' AND P.product_status='1' AND M.user_status='1' AND M.user_id='$user_id'");
												if(is_array($get_cart)){
													foreach($get_cart as $cart_val){
													?>
													<div class="form-group">
														<div class="col-sm-2 col-xs-12">
															<img alt="" class="img-responsive" src="<?php echo $cart_val['product_image_link']; ?>">
														</div>
														<div class="col-sm-4 col-xs-12 col-xs-12">
															<div class="col-xs-12"><h4><?php echo $cart_val['product_title']; ?></h4></div>
														</div>
														<div class="col-md-3 select_qty">
															<div class="input-group">
																<span class="input-group-btn">
																	<button type="button" id="mi_<?php echo $cart_val['product_id']; ?>" class="quantity-left-minus btn btn-default btn-number"  data-type="minus" data-field="">
																		<span class="glyphicon glyphicon-minus"></span>
																	</button>
																</span>
																<input type="text" id="quantity_<?php echo $cart_val['product_id']; ?>" name="quantity_<?php echo $cart_val['product_id']; ?>" class="myqty form-control input-number text-center cart_common" value="<?php echo $cart_val['cart_quantity']; ?>" min="1" max="100">
																<span class="input-group-btn">
																	<button type="button" id="pl_<?php echo $cart_val['product_id']; ?>" class="quantity-right-plus btn btn-default btn-number" data-type="plus" data-field="">
																		<span class="glyphicon glyphicon-plus"></span>
																	</button>
																</span>
															</div>
														</div>
														<?php
															$total_cart = round($total_cart + (round($cart_val['product_selling_price']*$cart_val['cart_quantity'],2)),2);
														?>
														<div class="col-sm-2 col-xs-12 text-right">
															<h6> &#8377;  <?php echo round($cart_val['product_selling_price']*$cart_val['cart_quantity'],2); ?> </h6>
														</div>
														<div class="col-sm-1 col-xs-12 text-right">
															<h6><span><a onclick="remove_cart('<?php echo $cart_val['cart_id']; ?>')" class="closeme"><i class="fa fa-times"></i> </a></span></h6>
														</div>
													</div>
													<div class="form-group"><hr /></div>
													<?php
													}
												}
											?>
											<div class="form-group">
												<div class="col-xs-12">
													<strong>Order Total</strong>
													<div class="pull-right">&#8377; <?php echo $total_cart; ?></div>
												</div>
											</div>
											<div class="form-group"><hr /></div>
										</div>
										
										<div class="form-group text-right">
											<div class="col-xs-12">
												<strong><a href="products.php" class="btn custombutton button--isi btn-primary">CONTINUE SHOPPING</a></strong>
											</div>
										</div>
									</div>
									<!--REVIEW ORDER END-->
								</div>
							</form>
						</div>
					</div><!-- end col -->
					<div class="col-md-6">
						<div class="panel panel-info">
							<div class="panel-heading">Shipping Address</div>
							<div class="panel-body">
								<div class="form-group">
									<div class="col-md-12"><strong>Country:</strong></div>
									<div class="col-md-12">
										<input type="text" class="form-control" name="user_country" id="user_country" value="<?php echo $get_user[0]['user_country']; ?>">
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-6 col-xs-12">
										<strong>First Name:</strong>
										<input type="text" name="user_username" id="user_username" class="form-control" value="<?php echo $get_user[0]['user_username']; ?>">
										<span class="span_err" id="user_username_err"></span> 
									</div>
									<!-- <div class="span1"></div> -->
									<div class="col-md-6 col-xs-12">
										<strong>Last Name:</strong>
										<input type="text" name="user_lastname" id="user_lastname" class="form-control" value="<?php echo $get_user[0]['user_lastname']; ?>">
										<span class="span_err" id="user_lastname_err"></span> 
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-12"><strong>Address Line 1:</strong></div>
									<div class="col-md-12">
										<input type="text" name="user_address1" id="user_address1" class="form-control" value="<?php echo $get_user[0]['user_address1']; ?>">
										<span class="span_err" id="user_address1_err"></span>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-12"><strong>Address Line 2:</strong></div>
									<div class="col-md-12">
										<input type="text" name="user_address2" id="user_address2" class="form-control" value="<?php echo $get_user[0]['user_address2']; ?>">
										<span class="span_err" id="user_address2_err"></span>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-12"><strong>State:</strong></div>
									<div class="col-md-12">
										<input type="text" name="user_state" id="user_state" class="form-control" value="<?php echo $get_user[0]['user_state']; ?>">
										<span class="span_err" id="user_state_err"></span>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-12"><strong>City:</strong></div>
									<div class="col-md-12">
										<input type="text" name="user_city" id="user_city" class="form-control" value="<?php echo $get_user[0]['user_city']; ?>">
										<span class="span_err" id="user_city_err"></span>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-12"><strong>Zip / Postal Code:</strong></div>
									<div class="col-md-12">
										<input type="text" name="user_pincode" id="user_pincode" class="form-control" value="<?php echo $get_user[0]['user_pincode']; ?>">
										<span class="span_err" id="user_pincode_err"></span>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-12"><strong>Phone Number:</strong></div>
									<div class="col-md-12">
										<input type="text" name="user_phone" id="user_phone" class="form-control" value="<?php echo $get_user[0]['user_phone']; ?>">
										<span class="span_err" id="user_phone_err"></span> 
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-12"><strong>Email Address:</strong></div>
									<div class="col-md-12"><input type="text" name="user_email" id="user_email" disabled readonly class="form-control" value="<?php echo $get_user[0]['user_email']; ?>"></div>
								</div>
								<div class="col-xs-12 pull-right">
									<strong class="pull-right"><button type="button" id="checkout" class="btn custombutton button--isi btn-primary pull-right">Proceed to Pay</button></strong>
									
								</div>
							</div>
							
						</div>
					</div>
				</div><!-- end row -->
			</div><!-- end container -->
		</section>
		
		
		
		<?php require_once("includes/footer.php");?>
		<script type="text/javascript">
			
			// Cart Functionality //
			function cart_add(pid,qty,opt){
				var formdata = new FormData();
				formdata.append('product_id', pid);
				formdata.append('quantity', qty);
				formdata.append('type', "1");
				formdata.append('opt', opt);
				$.ajax({
					type: "POST",
					data: formdata,
					processData: false,
					contentType: false,
					url: "cart_page_action.php",
					beforeSend: function () {
						$("#cart_body").html('<img src="images/ajax_loader.gif" alt="loader image" />');
					},
					success: function (msg) {
						msg = msg.trim();
						//alert(msg);
						if(msg == "2"){
							var message = "Something went wrong. Please try again";
							show_MessageTimout(message,2000);
						}
						else{
							var message = "Cart Updated Successfully";
							show_MessageTimout(message,2000);
							$("#cart_body").html(msg);
						}
					}
				}),
				$.ajax({
					type: "POST",
					data: formdata,
					processData: false,
					contentType: false,
					url: "refresh_menu_cart.php",
					beforeSend: function () {
						$("#cart_menu").html('<img src="images/ajax_loader.gif" alt="loader image" />');
					},
					success: function (msg) {
						msg = msg.trim();
						$("#cart_menu").html(msg);
					}
				});
				return false;
			}
			
			$(document).ready(function(){
				//
				// $('#element').donetyping(callback[, timeout=1000])
				// Fires callback when a user has finished typing. This is determined by the time elapsed
				// since the last keystroke and timeout parameter or the blur event--whichever comes first.
				//   @callback: function to be called when even triggers
				//   @timeout:  (default=1000) timeout, in ms, to to wait before triggering event if not
				//              caused by blur.
				// Requires jQuery 1.7+
				//
				(function($){
					$.fn.extend({
						donetyping: function(callback,timeout){
							timeout = timeout || 1e3; // 1 second default timeout
							var timeoutReference,
							doneTyping = function(el){
								if (!timeoutReference) return;
								timeoutReference = null;
								callback.call(el);
							};
							return this.each(function(i,el){
								var $el = $(el);
								// Chrome Fix (Use keyup over keypress to detect backspace)
								// thank you @palerdot
								$el.is(':input') && $el.on('keyup keypress paste',function(e){
									// This catches the backspace button in chrome, but also prevents
									// the event from triggering too preemptively. Without this line,
									// using tab/shift+tab will make the focused element fire the callback.
									if (e.type=='keyup' && e.keyCode!=8) return;
									
									// Check if timeout has been set. If it has, "reset" the clock and
									// start over again.
									if (timeoutReference) clearTimeout(timeoutReference);
									timeoutReference = setTimeout(function(){
										// if we made it here, our timeout has elapsed. Fire the
										// callback
										doneTyping(el);
									}, timeout);
									}).on('blur',function(){
									// If we can, fire the event since we're leaving the field
									doneTyping(el);
								});
							});
						}
					});
				})(jQuery);
				
				$('.cart_common').donetyping(function(){
					if($(this).val() == "" || isNaN($(this).val())){
						$(this).val(1);
					}
					var cart_curr_value = $(this).val();
					var cart_curr_id = $(this).attr("id");
					var pid = cart_curr_id.substring(9);
					var opt = "yes";
					
					cart_add(pid,cart_curr_value,opt);
				});
				
				var quantitiy=0;
				$('.quantity-right-plus').click(function(e){
					e.preventDefault();
					var curr_id = $(this).attr("id");
					qty_append = curr_id.substring(3);
					var quantity = parseInt($('#quantity_'+qty_append).val());
					if(isNaN(quantity) || quantity == 0){
						var quantity = parseInt($('#quantity_'+qty_append).val(1));
					}
					if(quantity>0){
						$('#quantity_'+qty_append).val(parseInt(quantity + 1));
					}
					
					var pid = qty_append;
					var cart_curr_value = $('#quantity_'+qty_append).val();
					var opt = "yes";
					cart_add(pid,cart_curr_value,opt);
				});
				
				$('.quantity-left-minus').click(function(e){
					e.preventDefault();
					var curr_id = $(this).attr("id");
					qty_append = curr_id.substring(3);
					var quantity = parseInt($('#quantity_'+qty_append).val());
					if(isNaN(quantity) || quantity == 0){
						var quantity = parseInt($('#quantity_'+qty_append).val(1));
					}
					if(quantity>1){
						$('#quantity_'+qty_append).val(parseInt(quantity - 1));
						
						var pid = qty_append;
						var cart_curr_value = $('#quantity_'+qty_append).val();
						var opt = "yes";
						cart_add(pid,cart_curr_value,opt);
					}
					
				});
				
			});
			// Cart Functionality //
			
			
			// Checkout Functionality //
			
			$("#checkout").click(function(){
				var user_country = $("#user_country").val().trim();
				var user_username = $("#user_username").val().trim();
				var user_lastname = $("#user_lastname").val().trim();
				var user_address1 = $("#user_address1").val().trim();
				var user_address2 = $("#user_address2").val().trim();
				var user_city = $("#user_city").val().trim();
				var user_state = $("#user_state").val().trim();
				var user_pincode = $("#user_pincode").val().trim();
				var user_phone = $("#user_phone").val().trim();
				
				if(user_country == ''){
					var message = "Please Enter a Country Name";
					valid('user_country',message);
					return false;
				}
				else if(user_address1 == ''){
					var message = "Please Enter some address detail";
					valid('user_address1',message);
					return false;
				}
				else if(user_address1 == ''){
					var message = "Please Enter some address detail";
					valid('user_address1',message);
					return false;
				}
				else if(user_state == ''){
					var message = "Please Enter a State Name";
					valid('user_state',message);
					return false;
				}
				else if(user_city == ''){
					var message = "Please Enter a City Name";
					valid('user_city',message);
					return false;
				}
				else if(user_pincode == '' || isNaN(user_pincode) || user_pincode.length != 6){
					var message = "Please Enter a valid 6 digit pincode";
					valid('user_pincode',message);
					return false;
				}
				else if(user_phone == '' || isNaN(user_phone) || user_phone.length != 10){
					var message = "Please Enter a valid Phone Number";
					valid('user_phone',message);
					return false;
				}
				else{
					alert(111);
					return false;
				}
			});
			
			
			// Checkout Functionality //
		</script>
		<?php
		}
		else{
		?>
		<script>
			window.location.href="logout.php";
		</script>
		<?php
		}
	}
	else{
	?>
	<script>
		window.location.href="logout.php";
	</script>
	<?php
	}
?>
</body>
</html>