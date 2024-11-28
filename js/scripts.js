// Применение токена при загрузке страницы
function addReCaptchaToken() {
  grecaptcha.ready(function () {
    grecaptcha
      .execute("6Leim_UpAAAAAF3aikFiTuxQhpLu8Viw2WNnvUk7", { action: "submit" })
      .then(function (token) {
        var recaptchaResponse = document.getElementById("g-recaptcha-response");
        recaptchaResponse.value = token;
      });
  });
}
