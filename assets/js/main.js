document.addEventListener("DOMContentLoaded", function () {
    const dynamicSection = document.querySelector(".main-page-dynamic-content");
    const navLinks = document.querySelectorAll(".navigation-options a");

    function loadPage(page) {
        fetch(`?page=${page}`)
            .then(response => response.text())
            .then(data => {
                // Extract only the content inside .main-page-dynamic-content
                const parser = new DOMParser();
                const htmlDoc = parser.parseFromString(data, "text/html");
                dynamicSection.innerHTML = htmlDoc.querySelector(".main-page-dynamic-content").innerHTML;
                history.pushState(null, "", `?page=${page}`); // Update URL without refreshing
            })
            .catch(error => console.error("Error loading page:", error));
    }

    navLinks.forEach(link => {
        link.addEventListener("click", function (event) {
            event.preventDefault();
            const page = this.getAttribute("href").split("=")[1]; // Extract page name
            if (page) {
                loadPage(page);
            }
        });
    });

    // Handle back/forward browser navigation
    window.addEventListener("popstate", function () {
        const urlParams = new URLSearchParams(window.location.search);
        const page = urlParams.get("page") || "home.php";
        loadPage(page);
    });
});
