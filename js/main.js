document.addEventListener("DOMContentLoaded", () => {
    try {
        const navItem = document.querySelector(".nav__items"); // Selecting the nav items
        const openNavBtn = document.querySelector("#open__nav-btn"); // Selecting the open nav button
        const closeNavBtn = document.querySelector("#close__nav-btn"); // Selecting the close nav button

        const openNav = () => {
            navItem.style.display = "flex";
            openNavBtn.style.display = "none";
            closeNavBtn.style.display = "inline-block";
        };

        const closeNav = () => {
            navItem.style.display = "none";
            openNavBtn.style.display = "inline-block";
            closeNavBtn.style.display = "none";
        };

        openNavBtn.addEventListener("click", openNav);
        closeNavBtn.addEventListener("click", closeNav);

        const handleResize = () => {
            if (window.innerWidth > 1024) {
                navItem.style.display = "flex";
                openNavBtn.style.display = "none";
                closeNavBtn.style.display = "none";
            } else {
                navItem.style.display = "none";
                openNavBtn.style.display = "inline-block";
                closeNavBtn.style.display = "none";
            }
        };

        window.addEventListener("resize", handleResize);
        handleResize(); // Call once to set initial state
    } catch (error) {
        console.error("Error occurred in toggle functionality:", error);
    }

    if (window.innerWidth <= 1024) {
        try {
            const sidebar = document.querySelector("aside");
            const showSidebarBtn = document.querySelector("#show__sidebar-btn");
            const hideSidebarBtn = document.querySelector("#hide__sidebar-btn");

            if (showSidebarBtn && hideSidebarBtn) {
                const showSidebar = () => {
                    sidebar.style.left = "0";
                    showSidebarBtn.style.display = "none";
                    hideSidebarBtn.style.display = "inline-block";
                };

                const hideSidebar = () => {
                    sidebar.style.left = "-100%";
                    showSidebarBtn.style.display = "inline-block";
                    hideSidebarBtn.style.display = "none";
                };

                showSidebarBtn.addEventListener("click", showSidebar);
                hideSidebarBtn.addEventListener("click", hideSidebar);

                const handleSidebarResize = () => {
                    if (window.innerWidth > 1024) {
                        sidebar.style.left = "0";
                        showSidebarBtn.style.display = "none";
                        hideSidebarBtn.style.display = "none";
                    } else {
                        sidebar.style.left = "-100%";
                        showSidebarBtn.style.display = "inline-block";
                        hideSidebarBtn.style.display = "none";
                    }
                };

                window.addEventListener("resize", handleSidebarResize);
                handleSidebarResize(); // Call once to set initial state
            } else {
                console.error("Sidebar buttons not found in the DOM.");
            }
        } catch (error) {
            console.error("Error occurred in sidebar functionality:", error);
        }
    }

    // Verificar si jQuery está disponible antes de ejecutar código jQuery
    if (window.jQuery) {
        $(document).ready(function () {
            $(".options").on("click", function () {
                var post_id = $(this).attr("id").replace(/\D/g, '');
                var vote_type = $(this).data("vote-type");

                $.ajax({
                    type: 'POST',
                    url: 'vote.php',
                    dataType: 'json',
                    data: { post_id: post_id, vote_type: vote_type },
                    success: function (response) {
                        if (!response.error) {
                            $("#vote_up_count_" + response.post_id).text(response.vote_up);
                            $("#vote_down_count_" + response.post_id).text(response.vote_down);
                        } else {
                            alert("Error: " + response.error);
                        }
                    }
                });
            });
        });
    } else {
        console.warn("jQuery no está cargado, la funcionalidad de votos no funcionará.");
    }

    
});