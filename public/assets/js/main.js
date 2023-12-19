$(document).ready(()=>{

    /* Open Modal change picture */

    $('.profile .picture .btn').on('click',function(){
        $(this).parent().find('form').css('display','flex')
    })

    $('.profile .picture .cancel').on('click',function(){
        $('.profile .picture form').css('display','none') 
    })

    /* End open Modal change picture */

    /* Modal system */

    $('.close-modal').on('click',function(){
        $(this).parent().parent().parent().css('display','none');
    })

    $('.open-modal').on('click',function(){
        $('.modal-trick').css('display','flex');
        let url = $(this).data('url');
        $(".modal-trick a").attr("href", url)
    })

    /* end modal system */

    /* Open Menu mobile */

    $('.open-menu').on('click',function(){
        $('header .menu').addClass('display')
        $('header .close-menu').show();
        $('header .open-menu').hide();
    })

    $('.close-menu').on('click',function(){
        $('header .menu').removeClass('display')
        $('header .close-menu').hide();
        $('header .open-menu').show();
    })

    /* End open menu mobile */

    /* Start See medias button display */

        $('.trick-single .see_media').on('click', function(){
            $('.trick-single .gallery').addClass('visible');
            $('.trick-single .no_see_media').show();
            $('.trick-single .see_media').hide();
        })

        $('.trick-single .no_see_media').on('click', function(){
            $('.trick-single .gallery').removeClass('visible');
            $('.trick-single .no_see_media').hide();
            $('.trick-single .see_media').show();
        })

    /* End see medias button display */
    let contentAjax = $('#tricks-homepage .container .tricks')
    $('#tricks-homepage .loadmore').on('click', function (evt) {
        evt.preventDefault();
        page = $(this).attr('data-page');
        page ++;
        $(this).attr('data-page',page)
        $.ajax(
          {
            type: "GET",
            url: "/trick/page/" + page,
            beforeSend: function(){
                $('.loader-container').show()
            },
          }
          
        )
        .done(function (reponse) {
        console.log('here tricks')
          contentAjax.append(reponse);
          $('.loader-container').hide()
          checkLoadMore();
          current_page = $('#tricks-homepage .loadmore').attr('data-page')
        }).fail(function (reponse) {
        })
 
      })

      let contentAjaxComment = $('.trick-single #comments-trick .comments')
    $('.trick-single .loadmore').on('click', function (evt) {
        console.log('clicked');
        evt.preventDefault();
        page = $(this).attr('data-page');
        trick_id = $(this).attr('data-trick')
        page ++;
        $(this).attr('data-page',page)
        $.ajax(
          {
            type: "GET",
            url: "/comment/" +trick_id+ "/page/" + page,
            beforeSend: function(){
                $('.loader-container').show()
            },
          }
          
        )
        .done(function (reponse) {
            console.log('here comments');
          contentAjaxComment.append(reponse);
          $('.loader-container').hide()
          checkLoadMore();
          current_page = $('#comments-trick .loadmore').attr('data-page')
        }).fail(function (reponse) {
        })
 
      })
    // button go down to tricks homepage

    let button_go_down = $('.arrow-go-down');
    let button_go_up = $('.arrow-go-up');
    
    let tricks_homepage = $('#tricks-homepage');

    button_go_down.on('click',()=>{
        current_page = $('#tricks-homepage .loadmore').attr('data-page')
        $('body').animate({
            scrollTop: tricks_homepage.offset().top + 1
        }, 300);
    })

    button_go_up.on('click',()=>{
        $('body').animate({
            scrollTop: tricks_homepage.offset().top + 1
        }, 300);
    })

    // scroll homepage
    let current_page = $('#tricks-homepage .loadmore').attr('data-page');
    let hero_background = $('.hero .hero_background');
    let hero_height = $('.hero:not(.inmodifie)').height();
    let hero_body = $('.hero .text_hero')

    

    $(document).on('scroll',()=>{
        let positionClient = $(document).scrollTop();
        if(positionClient < hero_height){
            hero_background.css('opacity',((positionClient/hero_height) + 0.2))
            hero_body.css('opacity',(1 - positionClient/hero_height))
        }
        if(positionClient > hero_height){
            button_go_down.hide();
            if(current_page >= 2){
                button_go_up.show();
            }
        }
        else{
            button_go_down.show();
            button_go_up.hide();
        }
    })

    //Flash button close

    $('.alert-dismissible .btn-close').on('click',function(){
        $(this).parent().remove();
    })

    


    

})

function checkLoadMore(){
    let total_page = $('.loadmore').attr('data-all-page');
    let current_page = $('.loadmore').attr('data-page');

    if(current_page >= total_page){
        $('.loadmore').hide();
    }
}