<div class="sidebar" id="side_nav">
    <div class="logo">
        <img class="d-flex justify-content-center mx-auto w-100" height="100%" width="100%" src="{{ asset('images/logoccin.png') }}">

        <i class="fa-solid ms-5  fa-xmark btn-ferme" type="button"></i>
    </div>
    <span class="logo_name">Chambre de Commerce et d'Industrie du Niger</span>

    <div class="profile mt-4 p-2" data-bs-toggle="modal" data-bs-target="#profileModal" style="cursor: pointer;">
    @if(Auth::check())
        <img height="32px" src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('images/profile.png') }}" 
             alt="Photo de profil" class="img-profile">
    @endif
    <span class="profile_name ms-3">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
    <i class="fa-solid ms-5 fa-xmark btn-ferme" type="button"></i>

    
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

        <!-- Si l'utilisateur est finiancier, on montre le menu des ressortissants -->
        @if(auth()->user()->role === 'financier')
        <li class="item">
            <div class="iocn-link">
                <a href="#">
                <i class="fa-solid fa-users fs-5"></i>
                <span class="link_name">Gestion des ressortissants</span>
                </a>
                <i class="fa-solid fa-angle-down arrow"></i>
            </div>
            <ul class="sub-menu">
                <li class="{{ request()->is('financier/utilisateurs') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('financier.ressortissants') }}">
                        <i class="fa fa-paper-plane sub-menu-i"></i>Ressortissants
                    </a>
                </li>
                <li class="{{ request()->is('financier/utilisateurs/ajouter') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('financier.ressortissants.ajouter') }}">
                        <i class="fa fa-paper-plane sub-menu-i"></i>Ajouter un ressortissant
                    </a>
                </li>
            </ul>
        </li>

        <li class="item">
            <div class="iocn-link">
                <a href="#">
                <i class="fa-solid fa-tags fs-5"></i>
                <span class="link_name">Gestion des types de cotisation</span>
                </a>
                <i class="fa-solid fa-angle-down arrow"></i>
            </div>
            <ul class="sub-menu">
                <li class="{{ request()->is('financier/typecotisations') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('financier.typecotisations') }}">
                        <i class="fa fa-paper-plane sub-menu-i"></i>Type de cotisation
                    </a>
                </li>
                <li class="{{ request()->is('financier/typecotisations/ajouter') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('financier.typecotisations.ajouter') }}">
                        <i class="fa fa-paper-plane sub-menu-i"></i>Ajouter un type de cotisation
                    </a>
                </li>
            </ul>
        </li>

        <li class="item">
            <div class="iocn-link">
                <a href="#">
                <i class="fa-solid fa-clipboard-list fs-5"></i>
                <span class="link_name">Gestion des cotisations</span>
                </a>
                <i class="fa-solid fa-angle-down arrow"></i>
            </div>
            <ul class="sub-menu">
                <li class="{{ request()->is('financier/cotisations') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('financier.cotisations') }}">
                        <i class="fa fa-paper-plane sub-menu-i"></i> Suivi des cotisations
                    </a>
                </li>
                
            </ul>
        </li>

        
        <li class="item">
            <div class="iocn-link">
                <a href="#">
                <i class="fa-solid fa-envelope fs-5"></i>
                <span class="link_name">Gestion des lettres de cotisation</span>
                </a>
                <i class="fa-solid fa-angle-down arrow"></i>
            </div>
            <ul class="sub-menu">
                <li class="{{ request()->is('financier/lettrecotisations') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('financier.lettrecotisations.create') }}">
                        <i class="fa fa-paper-plane sub-menu-i"></i>Envoyer une lettre
                    </a>
                </li>
                <li class="{{ request()->is('financier/lettrecotisations') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('financier.lettrecotisations.index') }}">
                        <i class="fa fa-paper-plane sub-menu-i"></i>Gérer les lettres
                    </a>
                    
                </li>
                
            </ul>
        </li>
        @endif


        <!-- Si l'utilisateur est caissier, on montre le menu des ressortissants -->
        @if(auth()->user()->role === 'caissier')
        <li class="item">
            <div class="iocn-link">
                <a href="#">
                <i class="fa-solid fa-users fs-5"></i>
                <span class="link_name">Gestion des ressortissants</span>
                </a>
                <i class="fa-solid fa-angle-down arrow"></i>
            </div>
            <ul class="sub-menu">
                <li class="{{ request()->is('caissier/utilisateurs') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('caissier.ressortissants') }}">
                        <i class="fa fa-paper-plane sub-menu-i"></i>Ressortissants
                    </a>
                </li>
                <li class="{{ request()->is('caissier/utilisateurs/ajouter') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('caissier.ressortissants.ajouter') }}">
                        <i class="fa fa-paper-plane sub-menu-i"></i>Ajouter un ressortissant
                    </a>
                </li>
            </ul>
        </li>

        <li class="item">
            <div class="iocn-link">
                <a href="#">
                <i class="fa-solid fa-cash-register fs-5"></i>
                <span class="link_name">Gestion des paiements</span>
                </a>
                <i class="fa-solid fa-angle-down arrow"></i>
            </div>
            <ul class="sub-menu">
                <li class="{{ request()->is('caissier/paiement') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('caissier.paiement.index') }}">
                        <i class="fa fa-paper-plane sub-menu-i"></i> Enregistrement paiement
                    </a>
                </li>
               
            </ul>
            <ul class="sub-menu">
                <li class="{{ request()->is('caissier/paiement') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('caissier.paiement.indexPaiement') }}">
                        <i class="fa fa-paper-plane sub-menu-i"></i> Liste des paiements
                    </a>
                </li>
               
            </ul>
        </li>
        @endif

        
        @if(auth()->user()->role === 'ressortissant')
        
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





