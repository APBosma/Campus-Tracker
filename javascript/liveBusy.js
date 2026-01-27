import {getHours, findCurrTimeIndex} from './chronos.js';

// Sets the live busy-ness values
function currentBusyness(data, dbName, currentTime) {
    fetch("/Campus_Tracker/get_capacity.php?location=" + dbName)
    .then(res => res.json())
    .then(dbData => {
        if (!dbData || dbData.error) {
            console.error("Error from server:", dbData?.error);
            return;
        }
        // Grab HTML elements
        let levelName = document.getElementById("current-level");
        let levelCircle = document.getElementById("circle");

        const capacity = dbData.max_capacity;
        const intervalSize = Math.floor(capacity/4) // Forces round down for int division, learned this when I competed in Java

        // Location is closed
        if (currentTime == -1) {
            levelName.textContent = "Closed";
            levelCircle.style.backgroundColor = "grey";
            return;
        }

        // Assigns a value using the custom interval size
        // Ex. 23 people and the max capacity is 250. 250/4 is 62. 
        // 23 people / 62 would go to 0, so it's basically empty
        switch (Math.floor(data[currentTime]/intervalSize)) {
            case 0:
                levelName.textContent = "Empty";
                levelCircle.style.backgroundColor = "green"
                break;
            case 1:
                levelName.textContent = "Not Busy";
                levelCircle.style.backgroundColor = "lightgreen"
                break;
            case 2:
                levelName.textContent = "Somewhat Busy";
                levelCircle.style.backgroundColor = "yellow"
                break;
            case 3:
                levelName.textContent = "Busy";
                levelCircle.style.backgroundColor = "orange"
                break;
            case 4:
                levelName.textContent = "Full";
                levelCircle.style.backgroundColor = "Red"
                break;
        }

    })
    .catch(err => {
        console.error("Error loading database data", err);
    });
}

document.addEventListener("DOMContentLoaded", async function () {
    let titleElement = document.getElementById("title")
    let graphName = titleElement.textContent;
    const hours = await getHours(graphName);

    //checks valid locations for fetch
    const validLocations = ["cafeteria", "north_tower_gym", "subway"];
    const dbName = graphName.toLowerCase().replaceAll(" ", "_");
    if (!validLocations.includes(dbName)) {
        console.warn("No data available for this graph:", graphName);
        return;
    }

    //fetches data from get_data.php
    fetch("/Campus_Tracker/get_data.php?location=" + dbName)
        .then(res => res.json())
        .then(dbData => {
            if (!dbData || dbData.error) {
                console.error("Error from server:", dbData?.error);
                return;
            }
            const theData = dbData.map(row => row.count);
            currentBusyness(theData, dbName, findCurrTimeIndex(hours));
        })
        .catch(err => {
            console.error("Error loading database data", err);
        });
});
