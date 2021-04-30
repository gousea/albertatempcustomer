<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} {{ ucfirst(config('multiauth.prefix')) }}</title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="{{ asset('global_assets/css/icons/icomoon/styles.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/bootstrap_limitless.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/layout.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/components.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/colors.min.css') }}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

</head>

<body>

	<!-- Main navbar -->
	<div class="navbar navbar-expand-md navbar-dark">
		<a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
			<i class="icon-indent-decrease"></i>
		</a>
		<div class="navbar-brand m-0 pb-0 pt-2">
            <h4><b>Alberta Payments</b></h4>
		</div>
		<div class="d-md-none">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
				<i class="icon-tree5"></i>
			</button>
			<button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
				<i class="icon-paragraph-justify3"></i>
			</button>
		</div>

		<div class="collapse navbar-collapse" id="navbar-mobile">


			<span class="navbar-text ml-md-3 mr-md-auto"></span>

			<ul class="navbar-nav pull-right">
				<li class="nav-item dropdown" style="border-right: 1px solid;">
					<a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
                        <span> <b>{{ session()->get('storeName') }} [ {{ session()->get('sid') }}]</b> </span>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
                        @foreach (session()->get('stores') as $store)
                            <a class="dropdown-item" href="{{ route('dashboard') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('store-form{{ $store->id }}').submit();">
                                {{ $store->name }}
                            </a>
                            <form id="store-form{{ $store->id }}" action="{{ route('dashboard') }}" method="POST" style="display: none;">
                                @csrf
                                <input type="hidden" name="sid" id="" value="{{ $store->id }}">
                            </form>
                        @endforeach

					</div>
                </li>
                <li class="nav-item" style="border-right: 1px solid;">
                    <a href="#" class="navbar-nav-link">
                        <i class="icon-graph"></i>
                    </a>
                </li>
                <li class="nav-item" style="border-right: 1px solid;">
                    <a href="#" class="navbar-nav-link" >
                        <i class="icon-cog2"></i>
                    </a>
                </li>
                <li class="nav-item" style="border-right: 1px solid;">
                    <a href="#" class="navbar-nav-link">
                        <i class="icon-new-tab"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="navbar-nav-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                        <i class="icon-exit"></i>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>


			</ul>
		</div>
	</div>
	<!-- /main navbar -->

	<!-- Page content -->
	<div class="page-content">

		<!-- Main sidebar -->
		<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

			<!-- Sidebar mobile toggler -->
			<div class="sidebar-mobile-toggler text-center">
				<a href="#" class="sidebar-mobile-main-toggle">
					<i class="icon-arrow-left8"></i>
				</a>
				Navigation
				<a href="#" class="sidebar-mobile-expand">
					<i class="icon-screen-full"></i>
					<i class="icon-screen-normal"></i>
				</a>
			</div>
			<!-- /sidebar mobile toggler -->


			<!-- Sidebar content -->
			<div class="sidebar-content">

				<!-- User menu -->
				<div class="sidebar-user">
					<div class="card-body">
						<div class="media">
							<div class="mr-3">
								<a href="#"><img src="{{ asset('global_assets/images/placeholders/placeholder.jpg') }}" width="38" height="38" class="rounded-circle" alt=""></a>
							</div>
							<div class="media-body justify-content-center">
								<div class="media-title font-weight-semibold">Victoria Baker</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /user menu -->


				<!-- Main navigation -->
				<div class="card card-sidebar-mobile">
					<ul class="nav nav-sidebar" data-nav-type="accordion">

						<!-- Main -->
						<li class="nav-item">
							<a href="{{ route('dashboard') }}" class="nav-link <?php  if(\Request::is('dashboard')){ echo 'active'; } ?>">
								<i class="icon-home4"></i>
								<span>
									Dashboard
								</span>
							</a>
                        </li>
                        <li class="nav-item nav-item-submenu <?php  if(\Request::is('users/*')){ echo 'nav-item-expanded nav-item-open'; } ?> ">
                            <a href="#" class="nav-link <?php  if(\Request::is('administration.*')){ echo 'active'; } ?>"><i class="icon-stack"></i><span>User</span></a>
                            <ul class="nav nav-group-sub" data-submenu-title="JSON forms">
                                <li class="nav-item"><a href="{{ route('users') }}" class="nav-link">User</a></li>
                                <li class="nav-item"><a href="{{ route('users') }}" class="nav-link">User Group</a></li>
                            </ul>
                        </li>


					</ul>
				</div>
				<!-- /main navigation -->

			</div>
			<!-- /sidebar content -->

		</div>
		<!-- /main sidebar -->


		<!-- Main content -->
		<div class="content-wrapper">

            @section('main-content')

            @show



		</div>
		<!-- /main content -->

	</div>
    <!-- /page content -->

    <!-- Footer -->
	<div class="navbar navbar-expand-lg navbar-light">
		<div class="text-center d-lg-none w-100">
			<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
				<i class="icon-unfold mr-2"></i>
				Footer
			</button>
		</div>
		<div class="navbar-collapse collapse" id="navbar-footer">
			<span class="navbar-text ml-lg-auto">
				&copy; {{ date('Y') }} All Rights Reserved.
			</span>
		</div>
	</div>
	<!-- /footer -->

    <!-- Core JS files -->
	<script src="{{ asset('global_assets/js/main/jquery.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/loaders/blockui.min.js') }}"></script>
    <!-- /core JS files -->
    <script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

    <script src="{{ asset('assets/js/app.js') }}"></script>

	<!-- Theme JS files -->
	<script src="{{ asset('global_assets/js/plugins/visualization/d3/d3.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/visualization/d3/d3_tooltip.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/forms/styling/switchery.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/ui/moment/moment.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/pickers/daterangepicker.js') }}"></script>

	<script src="{{ asset('global_assets/js/demo_pages/dashboard.js') }}"></script>
    <!-- /theme JS files -->

	<!-- Theme JS files -->
	<script src="{{ asset('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/responsive.min.js')}}"></script>


	{{-- <script src="{{ asset('global_assets/js/demo_pages/form_inputs.js') }}"></script> --}}


	{{-- <script src="{{ asset('global_assets/js/demo_pages/datatables_responsive.js') }}"></script> --}}
    <!-- /theme JS files -->

	{{-- <script src="{{ asset('global_assets/js/plugins/forms/styling/switchery.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/forms/styling/switch.min.js') }}"></script>
    <script src="{{ asset('global_assets/js/demo_pages/form_checkboxes_radios.js') }}"></script> --}}

    <!-- Theme JS files -->
	{{-- <script src="{{ asset('global_assets/js/plugins/notifications/pnotify.min.js') }}"></script>
    <script src="{{ asset('global_assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('global_assets/js/demo_pages/form_multiselect.js') }}"></script> --}}


    {{-- <script src="{{ asset('global_assets/js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('global_assets/js/demo_pages/editor_ckeditor.js') }}"></script> --}}

    @section('custom-script')

    @show



</body>
</html>
