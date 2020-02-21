<section class="main-sidebar">
    <div class="container">
        <div class="user_panel">
            <div class="row">
                <div class="col-sm-12">
                    <div class="info">
                        <label>{{ auth()->user()->full_name }}</label>
                        <label class="online"> <i class="fa fa-circle text-success"></i> online </label>
                    </div>
                </div>
            </div>
        </div>
        <!-- Start Navigation -->
        <ul class="sidebar-menu tree">
            <li class="header">Navigation</li>
            @if(auth()->user()->is_admin)
                <li>
                    <a href="{{ route('staff.index') }}" class="btn bg-gray text-left brclr-red">
                        <i class="fa fa-users"></i> <span>Utilisateurs</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('charge.index') }}" class="btn bg-gray text-left brclr-red">
                        <i class="fa fa-users"></i> <span>Charges</span>
                    </a>
                </li>
            @endif
            <li>
                <a href="{{ route('home') }}" class="btn bg-gray   text-left">
                    <i class="fa fa-home"></i> <span>Accueil</span>
                </a>
            </li>
            <li>
                <a href="{{ route('truck.index') }}" class="btn bg-gray text-left brclr-red">
                    <i class="fa fa-users"></i> <span>Transport</span>
                </a>
            </li>
            <li>
                <a href="{{ route('provider.links') }}" class="btn bg-gray text-left brclr-red">
                    <i class="fa fa-users"></i> <span>Fournisseurs</span>
                </a>
            </li>
            <li>
                <a href="{{ route('saisie') }}" class="btn bg-gray text-left brclr-red">
                    <i class="fa fa-users"></i> <span>Saisie</span>
                </a>
            </li>
        </ul>
    </div>
</section>