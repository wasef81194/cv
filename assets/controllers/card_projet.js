window.onload = () => {
    let cards = document.querySelectorAll("[data-show-card]")
    const a = document.getElementById('location');
    // On boucle sur links
    for(card of cards){
        // On écoute le clic
        card.addEventListener("click", function(){
            window.location.href = a.getAttribute('href')
        })
    }
}