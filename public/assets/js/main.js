let $collectionHolder;

let $addNewItem = $('<a href="#" class="btn btn-info">Ajouter une image</a>');

$(document).ready(()=>{

    // button go down to tricks homepage

    let button_go_down = $('.arrow-go-down');
    console.log($('.arrow-go-down'))
    let tricks_homepage = $('#tricks-homepage');

    button_go_down.on('click',()=>{
        console.log("clicked")
        console.log(tricks_homepage.offset().top)
        $('body').animate({
            scrollTop: tricks_homepage.offset().top
        }, 300);
    })

    // scroll homepage

    let hero_background = $('.hero .hero_background');
    let hero_height = $('.hero').height();
    let hero_body = $('.hero .text_hero')

    $(document).on('scroll',()=>{
        let positionClient = $(document).scrollTop();
        if(positionClient < hero_height){
            hero_background.css('opacity',((positionClient/hero_height) + 0.2))
            hero_body.css('opacity',(1 - positionClient/hero_height))
        }
    })

    


    // get collection

    $collectionHolder = $('#picture_list');
    // add remove buton to existing items
    $collectionHolder.find('.panel').each(function(item){
        addRemoveButton(item);
    })


    

})
// add new items picture

// remove item picture

function addRemoveButton($panel){
    console.log($panel)
}