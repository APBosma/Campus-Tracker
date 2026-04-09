// This file gets the announcements for each location page

// Sources:
// I actually wrote a fair amount of this myself using our previously written JS + PHP files as references.
// I was having a hard time getting this to output so I ended up asking ChatGPT why I was recieving no output. Turns out
// using of and in are two very different things in a for loop so I was only returning the index. 
// I also had to look up "JS how to add new element to section" to figure out the append. I used the google AI example
// to help so I could append each announcement as its own div inside of the announcements div.

document.addEventListener("DOMContentLoaded", async function () {
    // Grab and initialize info from the HTML
    const titleElement = document.getElementById("title");
    let locationName = titleElement.textContent;
    const announcementsSpace = document.getElementById("announcementsy");

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