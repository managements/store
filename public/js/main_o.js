$(document).ready(function() {

    $('input').on("keypress", function(e) {
        // update submit input to Tab
        if (e.keyCode === 13) {
            var inputs = $(this).parents("table").eq(0).find(":input");
            var indexOfInput= inputs.index(this);

            if (indexOfInput < inputs.length - 1) {
                inputs[indexOfInput + 1].focus(); 
            }
        }
    });

    // content loading
    setTimeout(function(){
        $('#contentloading').hide();
    },10);

    // sidebar animate
    $('#to_small_sidebar').on('click',function(){
        var current_with = $('.adm .admin-header .left-part').css('width');
        if(current_with == '260px'){
            localStorage.setItem(to_small_sidebar, 'small');
            toSmallNav();
            
        }else{
            localStorage.setItem(to_small_sidebar, 'half');
            toHalfNav();
        }
    });


   //focus next input by pressing enter
    $(document).on('keydown', '.suivantjjs .form-group', function (e) {
        if (e.which == 13) {
            e.preventDefault();
            $('.suivantjjs').submit(false);
            var index = $('.suivantjjs .form-group').index(this) + 1;
            $('.suivantjjs .form-group').eq(index).find('input, select').focus();
        }
    });

    //.ajax_search_grand_div .ajax_search_content
    $(document).on('keyup', '.ajax_search_grand_div input', function (e) {
       if($(this).val()){
        $('.ajax_search_grand_div .ajax_search_content').show();
       }
       else{
        $('.ajax_search_grand_div .ajax_search_content').hide();
       }
    });
   


});



/*  ================== functions =================  */
function toSmallNav(){
    $('.adm .admin-header .left-part, .adm .main-sidebar').animate({width:'105px'});
    $('.adm .admin-header .right-part , .adm .content_section').animate({marginLeft: '105px'});
    $('.adm .admin-header .left-part .logo-lg').html(' <img src="/images/logo.png"style="width:25px;border-radius:50%;margin-top:-5px;background: #fff;padding: 2px">  ');
    $('.adm .main-sidebar .user_panel').css('padding','20px 0');
    $('.adm .main-sidebar .user_panel > div > div  ').attr('class', '').addClass('col-md-12');
    $('.adm .main-sidebar .user_panel > div > div > div ').css('text-align','center');
    $('.adm .main-sidebar .sidebar-menu > li > a span').hide();
}

function toHalfNav(){
    $('.adm .admin-header .left-part, .adm .main-sidebar').animate({width:'260px'});
    $('.adm .admin-header .right-part , .adm .content_section').animate({marginLeft: '260px'});
    $('.adm .admin-header .left-part .logo-lg').html('<img src="/images/logo.png"style="width:25px;border-radius:50%;margin-top:-5px;background: #fff;padding: 2px"> <b>Buta Pro</b> ');
    $('.adm .main-sidebar .user_panel').css('padding','20px 30px');
    $('.adm .main-sidebar .user_panel > div > div  ').attr('class', '').addClass('col-sm-12');
    $('.adm .main-sidebar .user_panel > div > div > div ').css('text-align','left');
    $('.adm .main-sidebar .sidebar-menu > li > a span').show();

}


function delete_form_conf(e){ 
    e.preventDefault();
    Swal.fire({
        title: 'Êtes-vous sûr?',
        text: "Voulez-vous supprimer cet utilisateur!",
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, supprimez-le!'
      }).then((result) => {
        if (result.value === true) {
            e.target.submit();
        }else{
            return false;
        }
      });
}


function restore_form_conf(e){ 
    e.preventDefault();
    Swal.fire({
        title: 'Êtes-vous sûr?',
        text: "Voulez-vous activez cet utilisateur!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, activez-le!'
      }).then((result) => {
        if (result.value === true) {
          setTimeout(function(){
            e.target.submit();
          },1000);
        }else{
            return false;
        }
      });
}