$(document).ready(function () {

    /* Start modify form */
    $('.input .modify .icon').on('click', function () {
        $('.trick-name-input').addClass('modifing')
    })

    $('[data-close]').on('click', function () {
        $('.trick-name-input').removeClass('modifing');
    })
    /* End modify form */


    /**
     * Delete picture and video in modify form
     */

    let links = document.querySelectorAll("[data-delete]");


    for (let link of links) {

        link.addEventListener("click", function (e) {

            e.preventDefault();
            let picture = $(this).parent().parent();
            let link = $(this).parent().attr('href');
            let modal = $('[data-delete-image]');
            let token = $(this).data('token');

            modal.show();

            $(modal.find('a')).on('click',function(e){
                e.preventDefault();

                fetch(link, {
                    method: "DELETE",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ "_token": token })
                }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            modal.hide();
                            picture.remove();
                        } else {
                            alert(data.error);
                        }
                    })
            })
        });
    }

    let linksVideo = document.querySelectorAll("[data-delete-video]");


    for (let link of linksVideo) {

        link.addEventListener("click", function (e) {

            e.preventDefault();

            let video = $(this).parent().parent();
            let link = $(this).parent().attr('href');
            let modal = $('[data-delete-video-trick]');
            let token = $(this).data('token');

            modal.show();

            $(modal.find('a')).on('click',function(e){

                e.preventDefault();

                fetch(link, {
                    method: "DELETE",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ "_token": token })
                }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            modal.hide();
                            video.remove();
                        } else {
                            alert(data.error);
                        }
                    })
            })
        });
    }

    /**
     * Change image
     */

    $('[data-modify-picture]').on('click',function(e){
        e.preventDefault();
        let link = $(this).attr('href')
        let imageId = $(this).attr('data-image-id')
        
        $('#trick_image').attr('data-current-link',link);
        $('#trick_image').attr('data-current-id',imageId);

        $('.modify-image-form').css('display','flex');
    })

    $('[data-close-modify]').on('click',function(e){
        e.preventDefault();
        $('#trick_image').val('');
        $('[data-save-picture]').hide();
        $('.modify-image-form').hide();
        $('#trick_image').removeAttr('data-current-link');
        $('#trick_image').removeAttr('data-current-id');
    })


    $('#trick_image').on('change', function(){
        $('[data-save-picture]').show();
        let file = document.querySelector('#trick_image').files[0];
        let fileName = "newPictureSend";
        if(file.name){
            fileName = file.name;
        }

        $('[data-save-picture]').on('click',function(){
            
            const formdata = new FormData();
            formdata.append('picture',file,fileName)
            formdata.append('trickImageId', $('#trick_image').attr('data-current-id'))
            fetch($('#trick_image').attr('data-current-link'), {
                method: "POST",
                body: formdata
            }).then(response => response.json())
                .then(data => {
                    console.log(data)
                    if (data.success) {
                        $('[data-save-picture]').hide();
                        $('#trick_image').val('');
                        $('#trick_image').removeAttr('data-current-link');
                        $('#trick_image').removeAttr('data-current-id');
                        $('.modify-image-form').hide();
                        let urlImage = $('.gallery .image [data-image-id="' + data.id + '"]').parent().find('.image-visual');
                        let assetUrl = urlImage.attr('src').split('-',1);
                        urlImage.attr('src',assetUrl + "-" + data.url);
                    } else {
                        alert(data.error);
                    }
                })
        })
            
        
    })

    /**
     * Open modal for modify video iframe
     */
    $('#trick_videos fieldset').each(function () {
        $(this).append('<span class="close btn btn-dark">Close</span>')
    })

    $('[data-modify-video]').on('click', function () {
        let element = $('#trick_videos fieldset[data-index=' + $(this).parent().data('index') + ']');
        element.css('display', 'flex');
        element.find('.close').on('click', function () {
            element.css('display', 'none');
        })
    })

    /* Start collection videos */

    let collection, buttonAdd, span;
    collection = document.querySelector('#videos');
    span = collection.querySelector('#videos > span');

    console.log(span)

    buttonAdd = document.createElement('div');
    buttonAdd.className = "add-video btn btn-dark";
    buttonAdd.innerText = "Add video";


    let newButton = span.append(buttonAdd);

    collection.dataset.index = collection.querySelectorAll("input").length;

    if ($('#trick_videos fieldset')) {
        collection.dataset.index = collection.querySelectorAll("fieldset").length;
        let fields = collection.querySelectorAll("#trick_videos fieldset")
        fields.forEach(function (elm, index) {
            elm.dataset.index = index;
        })
    }


    buttonAdd.addEventListener('click', function () {
        addButton(collection, newButton);
    })

    function addButton(collection, newButton) {
        let prototype = collection.dataset.prototype;
        let index = collection.dataset.index;

        prototype = prototype.replace(/__name__/g, index);

        let content = document.createElement("html");
        content.innerHTML = prototype;
        let newForm = content.querySelector("div");

        let buttonRemove = document.createElement("div");
        buttonRemove.type = "button";
        buttonRemove.className = "btn btn-danger";
        buttonRemove.id = 'delete-video-' + index;
        buttonRemove.innerText = "Remove video";

        newForm.append(buttonRemove);

        collection.dataset.index++;

        let buttonAdd = collection.querySelector('.add-video');

        span.insertBefore(newForm, buttonAdd);

        buttonRemove.addEventListener('click', function () {
            this.previousElementSibling.parentElement.remove();
            console.log('remove');
        })
    }
    /* End collection videos */



});

