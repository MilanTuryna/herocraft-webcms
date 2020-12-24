/**
 * @author Milan Turyna
 * @see https://turyna.eu
 * @see https://github.com/MilanTuryna
 */
(function (document) {
    let typeWriterEffect = new TypeWriterEffect(document.getElementsByClassName("js--typeWriter"), function () {});
    typeWriterEffect.getElementsArr().forEach(el => el.style.visibility = 'hidden');
    setTimeout(function () {
        typeWriterEffect.getElementsArr().forEach(el => el.style.visibility = 'visible');
        typeWriterEffect.start();
    }, 1000);
})(document);
(function (document, window) {
    let button = document.createElement("button");
    button.id = "backToTop";
    button.innerHTML = "<i class='fa fa-angle-up'></i>";
    button.classList.add("shadow");
    button.title = "Vrátit se nahoru";
    button.addEventListener('click', function() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    });
    button.style.display = 'none';
    window.onscroll = function () {
        if (document.body.scrollTop > 150 || document.documentElement.scrollTop > 150) {
            button.style.display = "block";
        } else {
            button.style.display = "none";
        }
    };
    document.body.appendChild(button);
})(document, window);
(function (document) {
    setTimeout(function () {
        [...document.getElementsByClassName("heavy-icon")].forEach(el => {
            setTimeout(() => {
                let ad = el.style.animationDuration;
                el.style.animationDuration = "3s";
                el.style.color = "rgba(0,0,0,0.7)";
                el.style.filter = "drop-shadow(0.35rem 0.35rem 0.4rem rgba(0, 0, 0, 0.3))";
                el.style.animationDuration = ad;
            }, 1000)
        });
    },500);
})(document);