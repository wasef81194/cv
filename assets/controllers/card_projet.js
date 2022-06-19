$(document).ready(function () {
  let form = document.querySelector(".form-contact");
  form.addEventListener("submit", function (e) {
    e.preventDefault();
    console.log("c envoyer");
    ajax_contact();
  });
});
function ajax_contact(url) {
  $.ajax({
    method: "POST",
    url: url,
    dataType: "HTML",
  })
    .done(function (response) {
      console.log('send correctly');
    })
    .fail(function (jxh, textmsg, errorThrown) {
      console.log(textmsg);
      console.log(errorThrown);
    });
}
