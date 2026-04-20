// Sets the live location status in the admin pages. For the live location status on regular pages, see liveBusy.js.
// This code sets both the circle and the status name.

import { getHours, findCurrTimeIndex } from './chronos.js';

const validLocations = ["cafeteria", "north_tower_gym", "subway"];

// fetch per-hour bucketed counts (same length/order as hours[])
async function fetchHourlyCounts(dbName, hours) {
  const hoursParam = encodeURIComponent(hours.join("|"));
  const url = `/Campus_Tracker/php/get_predicted_data.php?location=${dbName}&hours=${hoursParam}`;
  const res = await fetch(url, { cache: "no-store" });
  return await res.json();
}

async function updateLocationStatus(locationName) {

    const dbName = locationName.toLowerCase().replaceAll(" ", "_");

    if (!validLocations.includes(dbName)) {
        console.warn("Invalid location:", dbName);
        return;
    }

    const circle = document.getElementById(dbName + "-circle");
    const levelText = document.getElementById(dbName + "-level");

    const {hours, openTime, closeTime, openTime2, closeTime2} = await getHours(locationName);
    const hourlyData = await fetchHourlyCounts(dbName, hours);
    console.log(hourlyData);

    fetch("/Campus_Tracker/php/get_capacity.php?location=" + dbName)
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

            let actualIndex;
            for (let i=0; i<hours.length;i++) {
                if (hours[i] == currentIndex) {
                    actualIndex = i;
                }
            }

            const level = Math.floor(hourlyData[actualIndex].predicted / intervalSize);

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
}

document.addEventListener("DOMContentLoaded", function () {

    updateLocationStatus("North Tower Gym");
    updateLocationStatus("Cafeteria");
    updateLocationStatus("Subway");

});