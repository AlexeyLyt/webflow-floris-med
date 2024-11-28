document.addEventListener("DOMContentLoaded", function () {
  addReCaptchaTokens();
});

// Yandex.Metrika counter
(function (m, e, t, r, i, k, a) {
  m[i] =
    m[i] ||
    function () {
      (m[i].a = m[i].a || []).push(arguments);
    };
  m[i].l = 1 * new Date();
  (k = e.createElement(t)),
    (a = e.getElementsByTagName(t)[0]),
    (k.async = 1),
    (k.src = r),
    a.parentNode.insertBefore(k, a);
})(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

ym(52623163, "init", {
  id: 52623163,
  clickmap: true,
  trackLinks: true,
  accurateTrackBounce: true,
  webvisor: true,
});

// reCAPTCHA v3
{
  /* <script src="https://www.google.com/recaptcha/api.js?render=6Leim_UpAAAAAF3aikFiTuxQhpLu8Viw2WNnvUk7"></script> */
}
function loadRecaptcha() {
  const script = document.createElement("script");
  script.src =
    "https://www.google.com/recaptcha/api.js?render=6Leim_UpAAAAAF3aikFiTuxQhpLu8Viw2WNnvUk7";
  document.head.appendChild(script);
}
loadRecaptcha();
// Применение токена при загрузке страницы
function addReCaptchaTokens() {
  grecaptcha.ready(function () {
    grecaptcha
      .execute("6Leim_UpAAAAAF3aikFiTuxQhpLu8Viw2WNnvUk7", { action: "submit" })
      .then(function (token) {
        var recaptchaResponses = document.querySelectorAll('[id=g-recaptcha-response]');
        recaptchaResponses.forEach(function (element) {
          element.value = token;
        });
      });
  });
}

{
  /* <noscript><div><img src="https://mc.yandex.ru/watch/52623163" style="position:absolute; left:-9999px;" alt="" /></div></noscript> */
}
function addYandexMetrikaFallback() {
  const div = document.createElement("div");
  div.style.position = "absolute";
  div.style.left = "-9999px";
  const img = document.createElement("img");
  img.src = "https://mc.yandex.ru/watch/52623163";
  img.alt = "";
  div.appendChild(img);
  document.head.appendChild(div);
}
addYandexMetrikaFallback();
