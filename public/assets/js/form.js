$(document).ready(function () {

    /* Start modify form */
    $('.input .modify .icon').on('click', function () {
        $(this).parent().parent().find('.input-form').addClass('modifing')
    })

    $('[data-close]').on('click', function () {
        $(this).parent().removeClass('modifing');
    })
    /* End modify form */


    /**
     * Delete picture and video in modify form
     */

    let links = document.querySelectorAll("[data-delete]");


    for (let link of links) {

        link.addEventListener("click", function (e) {

            e.preventDefault();

            if (confirm("Do you want to delete this picture?")) {

                console.log(this.parentElement.parentElement)
                fetch(this.parentElement.getAttribute("href"), {
                    method: "DELETE",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ "_token": this.dataset.token })
                }).then(response => response.json())
                    .then(data => {
                        if (data.success) {

                            this.parentElement.parentElement.remove();
                        } else {
                            alert(data.error);
                        }
                    })
            }
        });
    }

    let linksVideo = document.querySelectorAll("[data-delete-video]");


    for (let link of linksVideo) {

        link.addEventListener("click", function (e) {

            e.preventDefault();

            if (confirm("Do you want to delete this video?")) {

                fetch(this.parentElement.getAttribute("href"), {
                    method: "DELETE",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ "_token": this.dataset.token })
                }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            let index = this.parentElement.parentElement.dataset.index
                            let elementDelete = $('#trick_videos fieldset[data-index=' + index + ']');
                            elementDelete.remove();
                            this.parentElement.parentElement.remove();
                        } else {
                            alert(data.error);
                        }
                    })
            }
        });
    }

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

