<!-- header --->
<header class="admin-header">
    <div class="left-part">
        <a href="{{ route('home') }}" class="logo">
            <span class="logo-mini"></span>
            <span class="logo-lg">
                <img src="{{ asset('images/logo.png') }}"
                style="width:25px;border-radius:50%;margin-top:-5px;background: #fff;padding: 2px">
                <b>Buta Pro</b> </span>
        </a>
    </div>
    <div class="right-part">
        <a href="#" class="float-left to_small_sidebar" id="to_small_sidebar" data-toggle="push-menu" role="button">
            <i class="fa fa-bars" aria-hidden="true"></i> 
        </a>
        <div class="ajax_search_grand_div">
            <select name="sr_choix" class="select-search-choix">
                <option value="1">Bon Commande</option>
                <option value="1">Bon livraison</option>
                <option value="1">Bon Chargement</option>
                <option value="1">Déchargement</option>
                <option value="1">Facture</option>
                <option value="1">Fournisseur</option>
            </select>
            <input type="text" placeholder="recherche" class="input-search-choix">
            <div class="ajax_search_content">
                <a href="#">CD244d102</a>
                <a href="#">CD14GHJ1A</a>
                <a href="#">CD2D55M87</a>
                <a href="#">CD7LP55dA</a>
            </div>
        </div>
       
        <div class="dropdown float-right">
            <a role="button" class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false"> 
               <i class="fas fa-chevron-down"></i>
                {{ auth()->user()->staff->full_name }}
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                <a class="dropdown-item" href="{{ route('psw.edit') }}">Changer le mot de passe</a>
                <div class="dropdown-divider"></div>
                <a href="#"
                   onclick="event.preventDefault();
               document.getElementById('logout-form').submit();"
                   class="dropdown-item btn3 flex-c-m size13 txt11 trans-0-4 m-l-r-auto">
                    Se déconnecter
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
     
    </div>
</header>