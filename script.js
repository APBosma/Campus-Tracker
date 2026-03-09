// source: https://www.youtube.com/watch?v=LiomRvK7AM8
function show_form(form_id) {
    document.querySelectorAll(".login-block").forEach(
        from => form_id.classList.remove("active")
    );
    document.getElementById(form_id).classList.add("active");
}