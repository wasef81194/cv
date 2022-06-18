let albums = document.querySelectorAll(".album");
// On boucle sur links
for (album of albums) {
  // On écoute le clic
  album.addEventListener("click", function () {
    console.log(this);
    //  window.location.href = this.getAttribute("href")
    // window.location.href = a.getAttribute('href')
  });
}
