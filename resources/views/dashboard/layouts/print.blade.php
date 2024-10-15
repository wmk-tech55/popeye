<!DOCTYPE html>
<html lang="{{ getLanguage() }}">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Ramada Dashboard Proudly Developed By Mix Code Team">
	<meta name="author" content="Mix Code Team">
	<meta name="author" content="Mix Code Team">

	<!-- language -->
	<meta name="language" content="{{ getLanguage() }}">

	<!-- Title -->
	<title>@lang('main.print') | @yield('section', '') | {{ getSettings()->name }}</title>

	<!-- Custom styles for this template-->
	<link href="{{ asset('/dashboard_assets/css/app.min'. getRtlDirection() .'.css') }}" rel="stylesheet">

	@yield('styles')
</head>

<body id="page-top" dir="{{ getDirection() }}" class="sidebar-toggled">

	<!-- Page Wrapper -->
	<div id="wrapper">

		<!-- Content Wrapper -->
		<div id="content-wrapper" class="d-flex flex-column">

			<!-- Main Content -->
			<div id="content">

				<!-- Begin Page Content -->
				<div class="container-fluid mb-5">

					@yield('content')

				</div>
				<!-- /.container-fluid -->

			</div>
			<!-- End of Main Content -->

		</div>
		<!-- End of Content Wrapper -->

	</div>
	<!-- End of Page Wrapper -->

	@yield('scripts')
</body>

</html>