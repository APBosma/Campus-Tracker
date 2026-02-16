document.addEventListener("DOMContentLoaded", async function () {
    let error = document.getElementById("announcement_error");

    const date = new Date();
    date.setHours(0, 0, 0, 0);
    const start_date = (document.getElementById("start_date")).valueAsDate;
    const end_date = (document.getElementById("end_date")).valueAsDate;

    if (start_date < date) {
        error.innerHTML = "Invalid start date. Please select a date after today.";
        return;
    }
    if (end_date < start_date) {
        error.innerHTML = "Invalid end date. Please select a date after or the same as the start.";
        return;
    }

});
