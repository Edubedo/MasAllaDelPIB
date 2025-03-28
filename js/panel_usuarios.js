document.getElementById("logo_admin").addEventListener("click", function(event) {
    let popup = document.getElementById("userPopup");
    if (popup.style.display === "block") {
        popup.style.display = "none";
    } else {
        popup.style.display = "block";
        popup.style.top = event.clientY + "px";
        popup.style.left = event.clientX + "px";
    }
});

document.addEventListener("click", function(event) {
    let popup = document.getElementById("userPopup");
    let logo = document.getElementById("logo_admin");
    if (popup.style.display === "block" && event.target !== popup && event.target !== logo) {
        popup.style.display = "none";
    }
});