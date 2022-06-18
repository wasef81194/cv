window.onload = () => {
  var actives = "";
  var storage = window.location.hash;
  let defaultActive = document.querySelectorAll('[data-toggle="tab"]');
  let buttons = document.querySelectorAll('[data-toggle="tab"]');
  divNav(storage);
  for (button of buttons) {
    //on recuperer le dernier lien sur lequel on a cliquer
    if (storage == button.getAttribute("href")) {
      button.classList.add("nav-link");
      button.classList.add("show");
      button.classList.add("active");
    }
    // On écoute le clic
    button.addEventListener("click", function () {
      //on recuperer l'élément activer
      actives = document.getElementsByClassName("nav-link active");
      console.log(actives);
      for (active of actives) {
        //on le desactive
        active.classList.remove("active");
        active.classList.remove("show");
      }
      //pour activer l'element cliquer
      this.classList.add("nav-link");
      this.classList.add("show");
      this.classList.add("active");
      var divShow = "#" + this.href.split("#")[1];
      divNav(divShow);
    });
  }
};
function divNav(divShow) {
  // on affiche la bonne div meme si on rechare la page
  if (divShow == "#profile-photos") {
    $("#profile-photos").show();
    $("#projet").hide();
    // divShow
  }
  if (divShow == "#projet") {
    $("#profile-photos").hide();
    $("#projet").show();
    // divShow
  }
}
