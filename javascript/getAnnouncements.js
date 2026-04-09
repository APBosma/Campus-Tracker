// This file gets the announcements for each location page

document.addEventListener("DOMContentLoaded", async function () {
    // Grab and initialize info from the HTML
    const titleElement = document.getElementById("title");
    const locationName = titleElement.textContent;
    const announcementsSpace = document.getElementById("announcements");

    // Get announcements for location
    fetch("/Campus_Tracker/get_announcements.php?location=" + locationName)
        .then(res => res.json())
            .then(dbData => {
                if (!dbData || dbData.error) {
                    console.error("Error from server:", dbData?.error);
                    return;
                }
                const allAnnouncements = dbData.map(row => row.message);

                // Add announcements to the page
                for (let msg in allAnnouncements) {
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