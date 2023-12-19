$(document).ready(function (){

    /* Start modify form */
    $('.input .modify .icon').on('click',function(){
        $(this).parent().parent().find('.input-form').addClass('modifing')
    })
    /* End modify form */


    /* Start collection videos */

   let collection, buttonAdd, span;
   collection = document.querySelector('#videos');
   span = collection.querySelector('#videos span');

   console.log(span)

   buttonAdd = document.createElement('div');
   buttonAdd.className = "add-video btn btn-dark";
   buttonAdd.innerText = "Add video";


   let newButton = span.append(buttonAdd);

   collection.dataset.index = collection.querySelectorAll("input").length;
   

   buttonAdd.addEventListener('click',function(){
        addButton(collection, newButton);
   })

   function addButton(collection, newButton){
        let prototype = collection.dataset.prototype;
        let index = collection.dataset.index;

        prototype = prototype.replace(/__name__/g, index);

        let content = document.createElement("html");
        content.innerHTML = prototype;
        let newForm = content.querySelector("div");

        let buttonRemove = document.createElement("div");
        buttonRemove.type= "button";
        buttonRemove.className = "btn btn-danger";
        buttonRemove.id = 'delete-video-' + index;
        buttonRemove.innerText = "Remove video";

        newForm.append(buttonRemove);

        collection.dataset.index++;

        let buttonAdd = collection.querySelector('.add-video');

        span.insertBefore(newForm, buttonAdd);

        buttonRemove.addEventListener('click',function(){
            this.previousElementSibling.parentElement.remove();
            console.log('remove');
        })
    }
    /* End collection videos */


    /* Start collection pictures */

   let collectionPictures, buttonAddPicture, spanPicture;
   collectionPictures = document.querySelector('#pictures');
   spanPicture = collectionPictures.querySelector('#pictures span');

   buttonAddPicture = document.createElement('div');
   buttonAddPicture.className = "add-picture btn btn-dark";
   buttonAddPicture.innerText = "Add picture";


   let newButtonPicture = spanPicture.append(buttonAddPicture);

   collectionPictures.dataset.index = collectionPictures.querySelectorAll("input").length;
   

   buttonAddPicture.addEventListener('click',function(){
    addButtonPictures(collectionPictures, newButtonPicture);
   })

   function addButtonPictures(collectionPictures, newButtonPicture){
    let prototype = collectionPictures.dataset.prototype;
    let index = collectionPictures.dataset.index;

    prototype = prototype.replace(/__name__/g, index);

    let content = document.createElement("html");
    content.innerHTML = prototype;
    let newForm = content.querySelector("div");

    let buttonRemove = document.createElement("div");
    buttonRemove.type= "button";
    buttonRemove.className = "btn btn-danger";
    buttonRemove.id = 'delete-picture-' + index;
    buttonRemove.innerText = "Remove picture";

    newForm.append(buttonRemove);

    collectionPictures.dataset.index++;

    let buttonAddPicture = collectionPictures.querySelector('.add-picture');

    spanPicture.insertBefore(newForm, buttonAddPicture);

    buttonRemove.addEventListener('click',function(){
        this.previousElementSibling.parentElement.remove();
        console.log('remove');
    })

    
    
    }
    /* End collection videos */



});

