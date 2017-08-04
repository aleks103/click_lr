@extends('layouts.usersIndex')
@section('title', 'Billing & Upgrade')
@section('content')
	<link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/extra/fancybox/source/jquery.fancybox.css') }}" media="screen"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-buttons.css') }}" media="screen"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-thumbs.css') }}" media="screen"/>
	<div class="text-center animated fadeInDown">
		@include('errors.errors')
		<div class="ibox">
			<div class="ibox-heading p-h-xs">
				<div class="ibox-title">
					<div class="col-sm-5">
						<h2 class="font-bold text-left">Billing & Upgrade</h2>
					</div>
				</div>
			</div>
			<div class="ibox-content">
				<h4 class="text-left">
					Payment information
				</h4>
				<div class="auto-overflow table-responsive">
					@if($user_payment_array && sizeof($user_payment_array) > 0)
						<table class="table table-striped table-bordered table-hover">
							<thead>
							<tr>
								<th>PLAN DETAILS</th>
								<th>AMOUNT</th>
								<th>DURATION</th>
								<th>ACTIVATED ON</th>
								<th>EXPIRES ON</th>
								<th>Maximum CLICKS PER MONTH</th>
								<th>STATUS</th>
							</tr>
							</thead>
							<tbody>
							@foreach($user_payment_array as $row)
								<tr class="selTr">
									<td class="text-left action-btn">
										<div>
											<span class="bold">
												Plan Name
											</span>
											<span>
												{{ $row['plan_name'] }}
												@if($current_plan['id'] == $row['id'])
													<div><strong>Current plan</strong></div>
												@endif
											</span>
										</div>
										<div>
											<span class="bold">Purchased on</span>
											<span>
												{{ date('dS M', strtotime($row['date_added'])) }},
												<small class="text-muted">{{ date('Y H:i:s', strtotime($row['date_added'])) }}</small>
											</span>
										</div>
										<div>
											<span class="bold">Type</span>
											<span>{{ $row['plan_type'] }}</span>
										</div>
										@if($row['plan_type'] == 1 && $row['addons_emails'] > 0)
											<div>
												<span class="bold">Addons email</span>
												<span>
													@if($row['addons_emails'] > 0)
														{{ $row['addons_emails'] }}
													@else
														-
													@endif
												</span>
											</div>
											<div>
												<span class="bold">Addons amount</span>
												<span>
													@if($row['addons_amount'] > 0)
														<span class="text-muted">$</span> <strong>{{ $row['addons_amount'] }}</strong>
													@else
														-
													@endif
												</span>
											</div>
										@elseif($row['plan_type'] == 2)
											<div>
												<span class="bold">Charge</span>
												<span><span class="text-muted">$</span><strong>{{ $row['amount'] }}</strong> for per click</span>
											</div>
										@endif
										@if($row['trial'] == '1')
											<p>
												As this is Trial plan, your plan will be changed to "<strong><a target="_blank" href="#">{{ $row['next_plan_name'] }}</a></strong>
												@if($row['expiry_on'] != '0000-00-00 00:00:00')
													{{ ' on ' }} {{ date('dS M, Y', strtotime($row['expiry_on'])) }}
												@endif
											</p>
										@endif
										@if($current_plan['id'] == $row['id'])
											<a class="btn-xs btn-info"
											   onclick="cancelSubscription('{{ $row['id'] }}')">
												Cancel plan
											</a>
										@endif
									</td>
									<td>
										@if($row['amount'] == "" || $row['amount'] == 0)
											<span class="text-navy">Free</span>
										@else
											<span class="text-muted">$</span><strong>{{ $row['amount'] }}</strong>
											@if($row['user_plan_type'] == 2)
												<span class="text-muted">{{ '/ click' }}</span>
											@endif
										@endif
									</td>
									<td>
										{{ $row['duration'] }} <span style="text-transform:capitalize"> {{ $row['duration_schedule'] }}</span> (s)
									</td>
									<td>
										@if($row['activated_on'] != '0000-00-00 00:00:00')
											{{ date('dS M', strtotime($row['activated_on'])) }},
											<small class="text-muted">{{ date('Y H:i:s', strtotime($row['activated_on'])) }}</small>
										@else
											-
										@endif
									</td>
									<td>
										@if($row['expiry_on'] != '0000-00-00 00:00:00')
											{{ date('dS M', strtotime($row['expiry_on'])) }},
											<small class="text-muted">{{ date('Y H:i:s', strtotime($row['expiry_on'])) }}</small>
										@else
											-
										@endif
									</td>
									<td>
										@if(($row['email_limit'] * 1) > 0)
											{{ number_format($row['email_limit']) }}
										@else
											<span class="text-success">Unlimited</span>
										@endif
									</td>
									<td>
										@if($row['user_plan_status'] == 'Active')
											<label class="label label-success">{{ 'Activated' }}</label>
										@elseif($row['user_plan_status'] == 'Expired')
											<label class="label label-danger">{{ $row['user_plan_status'] }}</label>
										@elseif($row['user_plan_status'] == 'Pending')
											<label class="label label-danger">{{ 'Not-activated' }}</label>
										@endif
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					@else
						<div class="alert alert-info m-t">There is no plan exists.</div>
					@endif
				</div>
				<!-- -- END : Payment Information -- -->

				<!-- -- START : Defferent Plan -- -->
				<h4 class="text-left">
					Want to Choose a Different Plan?
				</h4>
				<div class="auto-overflow table-responsive">
					<table class="table table-striped table-bordered table-hover">
						<thead>
						<tr>
							<th>PLAN NAME</th>
							<th>AMOUNT</th>
							<th>DURATION</th>
							<th>MAXIMUM CLICKS PER MONTH</th>
							<th>ACTION</th>
						</tr>
						</thead>
						<tbody>
						@if(sizeof($pricingArray) > 0)
							@foreach($pricingArray as $key => $values)
								<tr>
									<td class="plandetails_type">
										<a class="fn_planDetailsPopup" onclick="showDetail({{ $values['plan_id'] }})">
											{{ $values['plan_name'] }}
										</a>
									</td>
									<td>
										@if($values['amount'] == "" || $values['amount'] == 0)
											<strong>Free</strong>
										@else
											<span class="text-muted">$</span><strong>{{ $values['amount'] }}</strong>
											@if($values['plan_type_id'] == 2)
												<span class="text-muted">{{ "/ click" }}</span>
											@endif
										@endif
									</td>
									<td>
										{{ $values['duration'] }} <span style="text-transform:capitalize"> {{ $values['duration_schedule'] }}</span> (s)
									</td>
									<td>
										@if(($values['email_limit'] * 1) > 0)
											{{ number_format($values['email_limit']) }}
										@else
											<span class="text-success">Unlimited</span>
										@endif
									</td>
									<td>
										@if($current_plan['id'] == $values['plan_id'])
											<div class="label label-success">Current plan</div>
										@elseif(isset($not_activated_plans[$values['plan_id']]) && $not_activated_plans[$values['plan_id']] < 30 )
											<div class="label label-warning">
												{{ 'Processing' }}
											</div>
										@else
											<div class="btn-group">
												<a class="btn btn-xs btn-info" onclick="setCardChange({{ $values['plan_id'] }})">
													@if(sizeof($user_payment_array) > 0)
														@if($user_payment_array[0]['plan_level'] > $values['plan_level'])
															Downgrade <i class="glyphicon glyphicon-arrow-down"></i>
														@else
															Upgrade <i class="glyphicon glyphicon-arrow-up"></i>
														@endif
													@else
														Upgrade <i class="glyphicon glyphicon-arrow-up"></i>
													@endif
												</a>
											</div>
										@endif
									</td>
								</tr>
								<tr id="billingBlock_{{ $values['plan_id'] }}" class="hidden">
									<td class="difplan-view fn_billing_block" colspan="6">
										<div align="center" class="member-formbg">
											@if(trim($userInfo->vaulet_key) != '' && !isset($values['product_url']))
												<div>
													<input type="checkbox" id="card_change_{{$values['plan_id']}}" name="card_change" value="1"
													       onclick="billingUpgrade({{$values['plan_id']}})">
													<label>I wish to use a different/new credit card information.</label>
												</div>
											@endif
											<div class="m-t-10">
												@if(isset($values['product_url']))
													<p>
														After clicking "Purchase" button you will be redirected to Payment Gateway. Please enter your account email
														<strong>{{ $userInfo->email }}</strong>
													</p>
													<p>If you provide different email, then it will be consider as creating new account.</p>
													@if($current_plan['id'] > 0)
														<a id="billing_{{ $values['plan_id'] }}" onclick="doPurchase('{{ $values['product_url'] }}' )"
														   class="btn btn-success btn-xs m-t-12">
															<i class="fa fa-shopping-cart"></i> Purchase
														</a>
													@else
														<a id="billing_{{ $values['plan_id'] }}" href="{{ $values['product_url'] }}" class="btn btn-success btn-xs m-t-12">
															<i class="fa fa-shopping-cart"></i> Purchase
														</a>
													@endif
												@else
													@if(trim($userInfo->vaulet_key) != '')
														<a id="upgrade_{{ $values['plan_id'] }}" onclick="return addNewPlan({{ $values['plan_id'] }});" class="btn btn-xs btn-info"
														   href="javascript:void(0);">
															<i class="icon-ok"></i> Continue...
														</a>
														<a id="billing_{{ $values['plan_id'] }}" href="#" style="display:none" class="btn btn-success btn-xs fn_changeCard">
															<i class="fa fa-shopping-cart"></i> Purchase
														</a>
													@else
														<a id="billing_{{ $values['plan_id'] }}" href="#" class="btn btn-success btn-xs fn_changeCard">
															<i class="fa fa-shopping-cart"></i> Purchase
														</a>
													@endif
												@endif
											</div>
										</div>
									</td>
								</tr>
							@endforeach
						@else
							<tr>
								<td colspan="6">
									<div class="alert alert-info label-margin">
										There is no plan exists.
									</div>
								</td>
							</tr>
						@endif
						</tbody>
					</table>
				</div>
				<!-- -- End : Defferent Plan -- -->

				<!-- -- START : Billing History -- -->
				<h4 class="text-left">
					Billing History
				</h4>
				<div class="auto-overflow table-responsive">
					<table class="table table-striped table-bordered table-hover">
						<thead>
						<tr>
							<th>PLAN NAME</th>
							<th>AMOUNT</th>
							<th>TRANSACTION ID</th>
							<th>PURCHASED ON</th>
							<th>STATUS</th>
							<th>PAYMENT METHOD</th>
						</tr>
						</thead>
						<tbody>
						@if(sizeof($billing_details) > 0)
							@foreach($billing_details as $key => $values)
								<tr>
									<td>
										{{--@if($values['used_for'] == 'Addon_sify')--}}
											{{--{{ 'Addons : '.$values['plan_name'] }}--}}
										{{--@elseif($values['used_for'] == 'Adons')--}}
											{{--{{ $values['plan_name'].' - Email Addons' }}--}}
										{{--@elseif($values['used_for'] == 'import')--}}
											{{--{{ $values['plan_name'] }}--}}
										{{--@else--}}
											{{--{{ $values['plan_name'].' - Plan' }}--}}
										{{--@endif--}}
										{{ $values['plan_name'] }}
									</td>
									<td>
										<span class="text-muted">$</span>
										<strong>@if($values['amount'] != ''){{ $values['amount'] }}@else {{ '0' }} @endif</strong>
									</td>
									<td>
										@if((isset($values['payment_method']) &&  $values['payment_method'] == 'Paykickstart'))
											<span class="text-muted">{{ $values['transaction_id'] }}</span>
										@else
											<span class="text-muted">@if($values['transaction_id'] > 0){{ $values['transaction_id'] }}@endif</span>
										@endif
									</td>
									<td>
										<span class="text-muted">{{ $values['date_added'] }}</span>
									</td>
									<td>
										<?php
										$lbl_class = "";
										if ( ($values['status']) == "Inactive" )
											$lbl_class = "label-danger";
										elseif ( ($values['status']) == "Success" )
											$lbl_class = "label-success";
										elseif ( ($values['status']) == "Pending" )
											$lbl_class = "label-warning";
										?>
										<span class="label arrowed-in arrowed-in-right {{ $lbl_class }}">
									{{ $values['status'] }}
								</span>
									</td>
									<td>
								<span class="label label-success">
									@if((isset($values['payment_method']) &&  $values['payment_method'] == 'Paykickstart'))
										{{ $values['payment_method'] }}
									@else
										{{ (isset($values['payment_method']) &&  $values['payment_method'] == 'Paypal')?'Paypal':'Credit Card' }}
									@endif
								</span>
									</td>
								</tr>
							@endforeach
						@else
							<tr>
								<td colspan="6">
									<div class="alert alert-info label-margin">
										No Billing History found
									</div>
								</td>
							</tr>
						@endif
						</tbody>
					</table>
				</div>
				<!-- -- End : Billing History -- -->
			</div>
			<!--IBox Content End-->
		</div>
	</div>
@endsection
@section('scripts')
	<script type="text/javascript" src="{{ asset('/js/vendor/extra/fancybox/lib/jquery.mousewheel.pack.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/js/vendor/extra/fancybox/source/jquery.fancybox.pack.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-buttons.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-media.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/js/vendor/extra/fancybox/source/helpers/jquery.fancybox-thumbs.js') }}"></script>

	<script type="text/javascript">
		var BASE = '{{ request()->root() }}';
		var Token = JSON.parse(window.Laravel).csrfToken;
		var href_url = '{{ route('billingupgrade', ['sub_domain' => session()->get('sub_domain')]) }}';
	</script>
	<script type="text/javascript" src="{{ asset('/js/users/billingupgrade.js') }}"></script>
@endsection