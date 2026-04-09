// This file gets the announcements for each location page

document.addEventListener("DOMContentLoaded", async function () {
    // Grab and initialize info from the HTML
    const titleElement = document.getElementById("title");
    let locationName = titleElement.textContent;
    const announcementsSpace = document.getElementById("announcements");

    locationName = locationName.replaceAll(' ', '_');
    locationName = locationName.toLowerCase();

    // Get announcements for location
    fetch("/Campus_Tracker/php/get_announcements.php?location=" + locationName)
        .then(res => res.json())
            .then(dbData => {
                if (!dbData || dbData.error) {
                    console.error("Error from server:", dbData?.error);
                    return;
                }
                const allAnnouncements = dbData.map(row => row.message);

                // Add announcements to the page
                for (let msg of allAnnouncements) {
                    console.log(msg);
                    const announcements = document.createElement('div');
                    announcements.textContent = msg;
                    announcementsSpace.append(announcements);
                }
            })
            .catch(err => {
                console.error("Error loading database data", err);
            });
});