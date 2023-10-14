$(document).ready(()=>{
    let contentAjax = $('#tricks-homepage .container .tricks')
    $('.loadmore').on('click', function (evt) {
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
          contentAjax.append(reponse);
          $('.loader-container').hide()
          checkLoadMore();
          current_page = $('#tricks-homepage .loadmore').attr('data-page')
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
    let hero_height = $('.hero').height();
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

    


    

})

function checkLoadMore(){
    let total_page = $('.loadmore').attr('data-all-page');
    let current_page = $('.loadmore').attr('data-page');

    if(current_page >= total_page){
        $('.loadmore').hide();
    }
}