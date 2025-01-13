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

// cards motion
document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
                observer.unobserve(entry.target);
            }
        });
    }, {threshold: 0.1});

    document.querySelectorAll('.planets .container').forEach(container => {
        observer.observe(container);
    });
});


// drop down for the filters
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('dropdownButton').addEventListener('click', function () {
        document.getElementById('dropdownContent').classList.toggle('show');
    });
});


// likes for the users
document.addEventListener('DOMContentLoaded', function () {
    const likeButtons = document.querySelectorAll('.like-button');

    likeButtons.forEach(button => {
        button.addEventListener('click', function () {
            const planetId = this.getAttribute('data-planet-id');
            const likeCountElement = document.getElementById(`like-count-${planetId}`);
            const likeButton = this;
            const planetElement = document.getElementById(`planet-${planetId}`);

            fetch('like.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `id=${planetId}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        likeCountElement.textContent = data.likes;
                        if (data.liked) {
                            likeButton.classList.add('liked');
                        } else {
                            likeButton.classList.remove('liked');
                            planetElement.remove();
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });
});


