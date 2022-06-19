$(document).ready(function () {
  let form = document.querySelector(".form-contact");
  let loader = document.querySelector(".loader");
  let messageSucess = document.querySelector(".message-send");
  let messageError = document.querySelector(".message-erreur");
  $(loader).hide();
  $(messageSucess).hide();
  $(messageError).hide();
  form.addEventListener("submit", function (e) {
    e.preventDefault();
    ajax_contact(this.action, this, loader, messageSucess, messageError);
    $(loader).show();
  });
});
function ajax_contact(url, form, loader, messageSucess, messageError) {
  $.ajax({
    method: "POST",
    url: url,
    data: $(form).serialize(),
  })
    .done(function (response) {
      console.log("send correctly");
      form.reset();
      $(loader).hide("slow");
      $(messageSucess).show("slow").delay(20000).hide("slow");
    })
    .fail(function (jxh, textmsg, errorThrown) {
      $(loader).hide("slow");
      $(messageError).show("slow").delay(20000).hide("slow");
      console.log(textmsg);
      console.log(errorThrown);
    });
}
