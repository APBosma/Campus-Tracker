// This DOMContentLoaded function thing makes program wait until the HTML and stuff is done being setup before it runs the JS
// EVERYTHING WILL BREAK WITHOUT THIS! DO NOT DELETE!
document.addEventListener("DOMContentLoaded", function () {
    let titleElement = document.getElementById("title")
    let locationName = titleElement.textContent;

    // Verify location exists in db
    const validLocations = ["cafeteria", "north_tower_gym", "subway"];
    const dbName = locationName.toLowerCase().replaceAll(" ", "_");
    if (!validLocations.includes(dbName)) {
        console.warn("No data available for this graph:", graphName);
        return;
    }
});