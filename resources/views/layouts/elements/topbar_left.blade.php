<ul class="nav navbar-nav navbar-left-custom">
    <li class="user dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown">
            <img src="{{ url('/img/favicon.png') }}" alt="Deli Tutti" style="background: #fff;">
            <span>{{ false ? auth()->user()->name : 'Bienvenido' }}</span>
            <i class="caret"></i>
        </a>
        <ul class="dropdown-menu">
            <li><a href="#"><i class="fa fa-user"></i> Profile</a></li>
            <li><a href="#"><i class="fa fa-tasks"></i> Tasks</a></li>
            <li><a href="#"><i class="fa fa-cog"></i> Settings</a></li>
            <li><a href="{{ url('/logout') }}"><i class="fa fa-mail-forward"></i> {{ __('Cerrar sesión') }}</a></li>
        </ul>
    </li>
    <li><a class="nav-icon sidebar-toggle"><i class="fa fa-bars"></i></a></li>
</ul>