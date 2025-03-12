<div class="sidebar" id="side_nav">
    <div class="logo">
        <img class="d-flex justify-content-center mx-auto w-100" height="100%" width="100%" src="{{ asset('images/logoccin.png') }}">

        <i class="fa-solid ms-5  fa-xmark btn-ferme" type="button"></i>
    </div>
    <span class="logo_name">Chambre de Commerce et d'Industrie du Niger</span>

    <div class="profile mt-4 p-2">
        <img height="32px" class="img-profile" src="/images/img_profil.jpg"></i>
        <span class="profile_name ms-3">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
        <i class="fa-solid ms-5  fa-xmark btn-ferme" type="button"></i>
    </div>
    <ul class="nav-links">
        <li class="{{ request()->is(Auth::user()->role . '/dashboard') ? 'item menu active' : 'item menu' }}" >
            <a style="height: 50px;" href="{{ route(Auth::user()->role . '.dashboard') }}">
                <i class="fa-solid fa-house"></i>
                <span class="link_name">Tableau de bord</span>
            </a>
        </li>
        <!-- Si l'utilisateur est Admin, on montre le menu des utilisateurs -->
        @if(auth()->user()->role === 'admin')
        <li class="item">
            <div class="iocn-link">
                <a href="#">
                <i class="fa-solid fa-users fs-5"></i>
                <span class="link_name">Gestion des utilisateurs</span>
                </a>
                <i class="fa-solid fa-angle-down arrow"></i>
            </div>
            <ul class="sub-menu">
                <li class="{{ request()->is('admin/utilisateurs') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.utilisateurs') }}">
                        <i class="fa fa-paper-plane sub-menu-i"></i>Utilisateurs
                    </a>
                </li>
                <li class="{{ request()->is('admin/utilisateurs/ajouter') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.utilisateurs.ajouter') }}">
                        <i class="fa fa-paper-plane sub-menu-i"></i>Ajouter un utilisateur
                    </a>
                </li>
            </ul>
        </li>
        @endif


        
        <li class="footer">
            <div class="footer-details">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <div class="footer-content">
                        <img src="/images/box-arrow-right.svg" alt="footer">
                    </div>

                    <div class="name-job">
                        <div class="footer_name">Se déconnecter</div>
                    </div>
                </a>
            </div>
        </li>
    </ul>
</div>