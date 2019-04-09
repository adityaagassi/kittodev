                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
				@stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-camera"></i> Add Product </h2>
                </div>
                <div class="contentpanel">
                	@if(isset($errors) && count($errors) > 0)
                	<div class="row">
                		<div class="col-sm-12">
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <strong>
                                <?php var_dump($errors); ?>
								@if ($errors->has('name'))
								    Name is required<br>
								@endif
								@if ($errors->has('slug'))
								    Slug invalid<br>
								@endif
								@if ($errors->has('category_id'))
								    Please select a category<br>
								@endif
								@if ($errors->has('quantity'))
								    Quantity invalid<br>
								@endif
								@if ($errors->has('sell_price'))
								    Sell price invalid<br>
								@endif
								@if ($errors->has('buy_price'))
								    Buy price is invalid<br>
								@endif
								</strong>
                            </div>
                		</div>
                	</div>
			    	@endif
                    <div class="row">
                    	<div class="panel panel-default">
        					<div class="panel-body panel-body-nopadding">
        						<form id ="basicForm" class="form-horizontal form-bordered" method="post" action="{{ url("products/add")}}">
									<div class="form-group">
										<label class="col-sm-3 control-label">Name</label>
										<div class="col-sm-6 ">
											<input type="text" placeholder="Name" class="form-control" name="name" required />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Category</label>
										<div class="col-sm-6 ">
											<select class="form-control chosen-select" name="category_id" data-placeholder="Choose a Category...">
												<option value=""></option>
												@if(isset($categories) && count($categories) > 0)
												@foreach($categories as $category)
												<option value="{{ $category->id }}">{{ $category->name }}</option>
												@endforeach
												@endif
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Quantity</label>
										<div class="col-sm-6 ">
											<input type="number" placeholder="0" class="form-control" name="quantity" id="quantity" required value="1" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Sell Price</label>
										<div class="col-sm-6 ">
											<div class="input-group">
												<span class="input-group-addon">Rp</span>
												<input type="number" placeholder="0" class="form-control" name="sell_price" required value="0" />
												<span class="input-group-addon">.00</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Buy Price</label>
										<div class="col-sm-6 ">
											<div class="input-group">
												<span class="input-group-addon">Rp</span>
												<input type="number" placeholder="0" class="form-control" name="buy_price" required value="0" />
												<span class="input-group-addon">.00</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-3 col-sm-6">
											<button class="btn btn-primary">Submit</button>&nbsp;
											<a class="btn btn-default">Cancel</a>
										</div>
									</div>
								</form>
        					</div><!-- panel-body -->
      					</div><!-- panel -->
                    </div>
                </div>
                @stop

                @section('scripts')
				<script>
				    jQuery(document).ready(function() {
				        // Spinner
				        var quantity = jQuery('#quantity').spinner();
						quantity.spinner('value', 0);
				        // Chosen
						jQuery(".chosen-select").chosen({'width':'100%','white-space':'nowrap'});
				        // Basic Form
				        jQuery("#basicForm").validate({
				            highlight: function(element) {
				                jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
				            },
				            success: function(element) {
				                jQuery(element).closest('.form-group').removeClass('has-error');
				            }
				        });
				    });
				</script>
                @stop