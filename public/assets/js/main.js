let $collectionHolder;

let $addNewItem = $('<a href="#" class="btn btn-info">Ajouter une image</a>');

$(document).ready(()=>{
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