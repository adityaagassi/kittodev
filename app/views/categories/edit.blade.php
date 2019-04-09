                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
				@stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-tag"></i> Edit Category </h2>
                </div>
                <div class="contentpanel">
                	@if(isset($errors) && count($errors) > 0)
                	<div class="row">
                		<div class="col-sm-12">
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <strong>
								@if ($errors->has('name'))
								    Name is required
								@endif
								@if ($errors->has('slug'))
								    Slug invalid
								@endif
								</strong>
                            </div>
                		</div>
                	</div>
			    	@endif
                    <div class="row">
                    	<div class="panel panel-default">
        					<div class="panel-body panel-body-nopadding">
        						@if(isset($category))
        						<form id ="basicForm" class="form-horizontal form-bordered" method="post" action="{{ url("categories/$category->id/edit") }}">
									<div class="form-group">
										<label class="col-sm-3 control-label">Name</label>
										<div class="col-sm-6 ">
											<input type="text" placeholder="Name" class="form-control" name="name" required value="{{ $category->name }}" />
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-3 col-sm-6">
											<button class="btn btn-primary">Submit</button>&nbsp;
											<a class="btn btn-default">Cancel</a>
										</div>
									</div>
								</form>
								@endif
        					</div><!-- panel-body -->
      					</div><!-- panel -->
                    </div>
                </div>
                @stop

                @section('scripts')
				<script>
				    jQuery(document).ready(function() {

					    $(".mainpanel").css("height", "");
					    
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