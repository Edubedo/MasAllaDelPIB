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
        } catch (error) {
            console.error("Error occurred in sidebar functionality:", error);
        }
    }
});