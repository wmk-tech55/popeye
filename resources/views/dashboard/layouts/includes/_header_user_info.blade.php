<li class="nav-item dropdown no-arrow">
    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <span class="mr-2 text-gray-600 small">{{ auth()->user()->full_name }}</span>
        {{-- <img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60"> --}}
    </a>
    <!-- Dropdown - User Information -->
    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">

        <div class="dropdown-divider"></div>


        {{--   <a class="dropdown-item" href="{{ route('dashboard.users.edit',auth()->user()) }}"    >
            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
            @lang('main.edit_profile')
        </a>
        --}} 

        <a class="dropdown-item" href="{{ route('dashboard.users.edit_profile') }}">
            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
            @lang('main.edit_profile')
        </a>





        {{-- <div class="dropdown-divider"></div>
        @if (isLang('en'))
        <a class="dropdown-item d-flex align-items-center {{ isLang('ar') ? 'text-muted' : '' }}"
            href="{{ route('change.language', ['lang' => 'ar']) }}">
            <div class="mr-3">
            <div class="icon-circle bg-info">  
                    <i class="fas fa-language text-gray-400"></i>
                 </div>  
            </div>
            <div>
                عربي 
            </div>
        </a>
        @else
 
        <a class="dropdown-item d-flex align-items-center {{ isLang('en') ? 'text-muted' : '' }}"
            href="{{ route('change.language', ['lang' => 'en']) }}">
            <div class="mr-3">
                     <div class="icon-circle bg-info">  
                    <i class="fas fa-language text-gray-400"></i>
                 </div>  
            </div>
            <div>
                English 
            </div>
        </a>
 
        @endif --}}

        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
            @lang('main.logout')
        </a>
        {{-- <div class="dropdown-divider"></div>
        <a class="dropdown-item disabled" href="#">
            <i class="fas fa-tags fa-sm fa-fw mr-2 text-gray-400"></i>
            @lang('main.mx_mixcode_board_version') 0.5v
        </a> --}}
    </div>
</li>
