 <!-- ======= Header ======= -->
 <header id="header" class="fixed-top">
     <div class="container">

         <div class="logo float-left">
             <!-- Uncomment below if you prefer to use an text logo -->
             <h1><a href="/">{{ getSettings()->name }}</a></h1>
             {{--  <a href="index.html"><img src="assets/img/logo.png" alt="" class="img-fluid"></a> --}}
         </div>

         <nav class="main-nav float-right d-none d-lg-block">
             <ul>

                 <li class="{{ active_route('home') }}">
                     <a href="{{ route('home') }}">
                         <i class="fas fa-cog me-1"></i>
                         @lang('main.home')
                     </a>
                 </li>

                 <li class=" {{ active_route('terms_and_conditions') }}">
                     <a href="{{ route('terms_and_conditions') }}">
                         <i class="fas fa-cog me-1"></i>
                         @lang('site.terms_and_conditions')
                     </a>
                 </li>

                 <li class="{{ active_route('privacy_policy') }}">
                     <a href="{{ route('privacy_policy') }}">
                         <i class="fas fa-cog me-1"></i>
                         @lang('site.privacy_policy')
                     </a>
                 </li>
                 @auth

                     <li class=" {{ active_route('dashboard.dashboard') }}">
                         <a href="{{ route('dashboard.dashboard') }}">
                             <i class="fas fa-cog me-1"></i>
                             @lang('main.dashboard')
                         </a>
                     </li>


                     <li class="get-started">
                         <a href="#"
                             onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                             class="dropdown-item">@lang('site.logout')</a>

                         <form id="logout-form" action="{{ route('logout') }}" method="POST">
                             @csrf
                         </form>
                     </li>

                 @endauth


             </ul>
         </nav><!-- .main-nav -->

     </div>
 </header><!-- #header -->
