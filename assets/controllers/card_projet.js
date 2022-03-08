window.onload = () => {
    let cards = document.querySelectorAll("[data-show-card]")
    const a = document.getElementById('location');
    // On boucle sur links
    for(card of cards){
        // On écoute le clic
        card.addEventListener("click", function(){
            console.log(card,this)
            window.location.href = this.getAttribute("href")
           // window.location.href = a.getAttribute('href')
        })
    }
}