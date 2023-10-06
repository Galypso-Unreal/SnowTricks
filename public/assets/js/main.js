let $collectionHolder;

let $addNewItem = $('<a href="#" class="btn btn-info">Ajouter une image</a>');

$(document).ready(()=>{

    // scroll homepage

    let hero_background = $('.hero .hero_background');
    let hero_postion = $(".hero").position().top;
    let hero_heigth = $('.hero').height();
    console.log(hero_heigth)

    $(document).on('scroll',()=>{
        let positionClient = $(document).scrollTop();
        if(positionClient < hero_heigth){
            hero_background.css('opacity',((positionClient/hero_heigth) + 0.2))
        }
    })

    console.log($(".hero").position().top)

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