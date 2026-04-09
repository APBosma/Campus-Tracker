// This file gets the announcements for each location page

document.addEventListener("DOMContentLoaded", async function () {
    let titleElement = document.getElementById("title")
    let locationName = titleElement.textContent;

    fetch("/Campus_Tracker/get_data.php?location=" + locationName)
        .then(res => res.json())
            .then(dbData => {
                if (!dbData || dbData.error) {
                    console.error("Error from server:", dbData?.error);
                    return;
                }
                const allAnnouncements = dbData.map(row => row.count);
            })
            .catch(err => {
                console.error("Error loading database data", err);
            });
});