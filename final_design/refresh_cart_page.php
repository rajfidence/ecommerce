<?php
	require_once('includes/access.php');
	require_once('adminpanel/lib/helper.php');
	$user_id = base64_decode(base64_decode(base64_decode(base64_decode(addslashes($_SESSION['skinbae_user'])))));
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
	else{
	?>
	<script>
		window.location.href="products.php";
	</script>
	<?php
	}
?>
<div class="form-group">
	<div class="col-xs-12">
		<strong>Order Total</strong>
		<div class="pull-right">&#8377; <?php echo $total_cart; ?></div>
	</div>
</div>
<div class="form-group"><hr /></div>
<script>
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
			}
			
			var pid = qty_append;
			var cart_curr_value = $('#quantity_'+qty_append).val();
			var opt = "yes";
			cart_add(pid,cart_curr_value,opt);
		});
		
	});
	
</script>	