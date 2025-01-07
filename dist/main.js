<<<<<<< HEAD
document.addEventListener("DOMContentLoaded", () => {
    const shuttle = document.querySelector("model-viewer#curiosity"); // Selecteer het shuttle-element
=======
// Smooth scrolling to the top when clicking logo
const logo = document.querySelector("header .logo");
if (logo) {
    logo.addEventListener("click", (e) => {
        e.preventDefault();
        window.scrollTo({top: 0, behavior: "smooth"});
    });
}

// Scroll to the results section when searching
document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const search = urlParams.get("name"); // Get the 'name' parameter
    if (search) {
        const results = document.getElementById("planets");
        if (results) {
            results.scrollIntoView({behavior: "smooth"});
        }
    }
});


// shuttle
document.addEventListener("DOMContentLoaded", () => {
    const shuttle = document.querySelector("model-viewer#curiosity");
>>>>>>> f956906faf9c3a30d02d540ba1dc1a941e651880
    const exploreMoreButton = document.querySelector("a[href='#planets'] button");
    const screenHeight = window.innerHeight;
    const screenWidth = window.innerWidth;
    let positionX = -150; // Startpositie van de shuttle
    let positionY = -40;
    const animationSpeed = 5;
    let isAnimating = false;

    // Zet het shuttle op de startpositie aan het begin
    function stopAtStart() {
        shuttle.style.left = "150px";
        shuttle.style.top = `${positionY}px`;
        shuttle.style.transition = "none";
    }

    // Laat de shuttle naar binnen vliegen
    function flyIn() {
        positionX = -150; // de rustpositie
        function animate() {
            if (positionX < 150) {
                positionX += animationSpeed;
                shuttle.style.left = `${positionX}px`;
                shuttle.style.top = `${positionY}px`;
                requestAnimationFrame(animate);
            } else {
                stopAtStart();
            }
        }

        animate();
    }

    // Animeren tot het einde van het scherm
    function animateToEnd() {
        isAnimating = true;

        function animate() {
            if (positionX < screenWidth) {
                positionX += animationSpeed;
                shuttle.style.left = `${positionX}px`;
                shuttle.style.top = `${positionY}px`;
                requestAnimationFrame(animate);
                if (positionX >= screenWidth) {
                    shuttle.style.opacity = "0";
                    shuttle.style.transition = "opacity 0.5s ease";
                    window.scrollTo({top: screenHeight, behavior: "smooth"});
                }
                requestAnimationFrame(animate);
            } else {
                isAnimating = false;
                shuttle.style.display = "none";
            }
        }

        animate();
    }

    shuttle.addEventListener("load", () => {
        flyIn();
    });

    // Triggert animatie wanneer de knop wordt aangeklikt
    exploreMoreButton.addEventListener("click", (e) => {
        e.preventDefault();
        if (!isAnimating) {
            shuttle.style.opacity = "1";
            shuttle.style.display = "";
            positionX = 150;
            animateToEnd();
        }
    });
});


<<<<<<< HEAD
=======



>>>>>>> f956906faf9c3a30d02d540ba1dc1a941e651880
