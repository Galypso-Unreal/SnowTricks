$(document).ready(function (){

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

});

