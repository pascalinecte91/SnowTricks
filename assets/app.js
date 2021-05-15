/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import '@popperjs/core';
require('bootstrap');

// start the Stimulus application
import './bootstrap';

window.onload = () => {
    // liens supprimés  des images
    let links = document.querySelectorAll("[data-delete]");
    
    for (link of links) {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            //cherhcer à confrimer
            if(confirm(" voulez vous la supprimer")) {
                //  envoi la requete  Ajax vers le lien 
                fetch(this.getAttribute("href"), {
                    method: "DELETE",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({"_token": this.dataset.token})
                    // on envoie l'url à token
                }).then(
                    //il faut recuperer la reponse en json ! quand on utilise .then on a une variable un point une fleche et une focntion
                    response => response.json()
                ).then(data => {
                    if (data.success){
                        this.parentElement.remove();
                    }else {
                        alert(data.error);
                    }
                }).catch(e => alert(e))
            }
        });
    }
}