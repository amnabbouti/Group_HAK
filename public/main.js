// Smooth scrolling to the top when clicking logo
const logo = document.querySelector("header .logo");
if (logo) {
    logo.addEventListener("click", (e) => {
        e.preventDefault();
        window.scrollTo({top: 0, behavior: "smooth"});
    });
}


// shuttle
document.addEventListener("DOMContentLoaded", () => {
    const shuttle = document.querySelector("model-viewer#curiosity");
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




