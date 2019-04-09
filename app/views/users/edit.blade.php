                @extends('master')

				@section('stylesheets')
					<link href="{{ url("css/jquery.datatables.css") }}" rel="stylesheet">
				@stop

                @section('content')
                <div class="pageheader">
                    <h2><i class="fa fa-users"></i> Edit User </h2>
                </div>
                <div class="contentpanel">
                	@if(isset($errors) && count($errors) > 0)
                	<div class="row">
                		<div class="col-sm-12">
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <strong>
								@if ($errors->has('name'))
								    Name is required<br>
								@endif
								@if ($errors->has('email'))
								    Email is invalid<br>
								@endif
								@if ($errors->has('level_id'))
								    Please select a level<br>
								@endif
								@if ($errors->has('password'))
								    Password is invalid<br>
								@endif
								</strong>
                            </div>
                		</div>
                	</div>
			    	@endif
                    <div class="row">
                    	<div class="panel panel-default">
        					<div class="panel-body panel-body-nopadding">
        						<form id ="basicForm" class="form-horizontal form-bordered" method="post" action="{{ url("users/$user->id/edit")}}">
        							@if (Session::get('level_id') < 3)
									<div class="form-group">
										<label class="col-sm-3 control-label">Level <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<select class="form-control chosen-select" name="level_id" data-placeholder="Choose a Level..." required>
												<option value=""></option>
												@if(isset($levels) && count($levels) > 0)
												@foreach($levels as $level)
												@if($user->level_id == $level->id)
												<option value="{{ $level->id }}" selected>{{ $level->name }}</option>
												@else
												<option value="{{ $level->id }}">{{ $level->name }}</option>
												@endif
												@endforeach
												@endif
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Name <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<input type="text" class="form-control" placeholder="Your name" name="name" required value="{{ $user->name }}" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Email <span class="asterisk">*</span></label>
										<div class="col-sm-6 ">
											<input type="email" class="form-control" placeholder="ie. test@gmail.com" name="email" required value="{{ $user->email }}" />
										</div>
									</div>
									@else
										<input type="hidden" name="level_id" value="{{ $user->level_id }}" />
										<input type="hidden" name="name" value="{{ $user->name }}" />
										<input type="hidden" name="email" value="{{ $user->email }}" />
									@endif
									<div class="form-group">
										<label class="col-sm-3 control-label">New Password</label>
										<div class="col-sm-6 ">
											<input type="password" class="form-control" name="password" required/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Retype New Password</label>
										<div class="col-sm-6 ">
											<input type="password" class="form-control" name="password_confirmation" required />
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