import { getHours, findCurrTimeIndex } from './chronos.js';

const validLocations = ["cafeteria", "north_tower_gym", "subway"];

async function updateLocationStatus(locationName) {

    const dbName = locationName.toLowerCase().replaceAll(" ", "_");

    if (!validLocations.includes(dbName)) {
        console.warn("Invalid location:", dbName);
        return;
    }

    const circle = document.getElementById(dbName + "-circle");
    const levelText = document.getElementById(dbName + "-level");

    const {hours, openTime, closeTime, openTime2, closeTime2} = await getHours(locationName);

    fetch("/Campus_Tracker/get_data.php?location=" + dbName)
        .then(res => res.json())
        .then(dbData => {

            if (!dbData || dbData.error) {
                levelText.textContent = "Error";
                return;
            }

            const data = dbData.map(row => row.count);

            fetch("/Campus_Tracker/get_capacity.php?location=" + dbName)
                .then(res => res.json())
                .then(capData => {

                    const capacity = capData.max_capacity;
                    const intervalSize = Math.floor(capacity / 4);

                    const currentIndex = findCurrTimeIndex(hours, openTime, closeTime, openTime2, closeTime2);

                    if (currentIndex == -1) {
                        levelText.textContent = "Closed";
                        circle.style.backgroundColor = "grey";
                        return;
                    }

                    const level = Math.floor(data[currentIndex] / intervalSize);

                    switch (level) {
                        case 0:
                            levelText.textContent = "Empty";
                            circle.style.backgroundColor = "green";
                            break;
                        case 1:
                            levelText.textContent = "Not Busy";
                            circle.style.backgroundColor = "lightgreen";
                            break;
                        case 2:
                            levelText.textContent = "Somewhat Busy";
                            circle.style.backgroundColor = "yellow";
                            break;
                        case 3:
                            levelText.textContent = "Busy";
                            circle.style.backgroundColor = "orange";
                            break;
                        default:
                            levelText.textContent = "Full";
                            circle.style.backgroundColor = "red";
                            break;
                    }

                });
        })
        .catch(err => {
            console.error("Error loading data", err);
            levelText.textContent = "Error";
        });
}

document.addEventListener("DOMContentLoaded", function () {

    updateLocationStatus("North Tower Gym");
    updateLocationStatus("Cafeteria");
    updateLocationStatus("Subway");

});