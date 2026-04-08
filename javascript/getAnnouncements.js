// This file gets the announcements for each location page

document.addEventListener("DOMContentLoaded", async function () {
    fetch("/Campus_Tracker/get_data.php?location=" + dbName)
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