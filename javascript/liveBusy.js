// This code sets the live status for the regular location pages. To see how we set the status for the 
// admin pages, check adminLiveStatus.js. It sets both the circle color and the status name.
import { getHours, findCurrTimeIndex } from './chronos.js';

/**
 * Fetch max capcity for the location
 * 
 * @param {string} dbName - Name of the location formatted to match the database
 * @return {object[]} - stores the max capacity of the location. Use .max_capacity to access it.
 */
async function fetchCapacity(dbName) {
  const res = await fetch(`/Campus_Tracker/php/get_capacity.php?location=${dbName}&x=${Date.now()}`, { cache: "no-store" });
  return await res.json();
}

/**
 * Fetch per-hour bucketed counts (Same length/order as hours[])
 * 
 * @param {string} dbName - Name of the location formatted to match the database
 * @param {string[]} hours - An array with each hour the location is open 
 *                          Ex. ["6 am", "7 am", "8 am"]
 * @returns {object[]} - An object with the hourly counts. To access the hourly counts use .predicted
 */
async function fetchHourlyCounts(dbName, hours) {
  const hoursParam = encodeURIComponent(hours.join("|"));
  const url = `/Campus_Tracker/php/get_predicted_data.php?location=${dbName}&hours=${hoursParam}`;
  const res = await fetch(url, { cache: "no-store" });
  return await res.json();
}

/**
 * Updates the UI circle + label
 * 
 * @param {string} label - Level of live busyness
 * @param {string} color - Color corresponding with the level of busyness
 */
function setBusynessUI(label, color) {
  const levelName = document.getElementById("current-level");
  const levelCircle = document.getElementById("circle");
  levelName.textContent = label;
  levelCircle.style.backgroundColor = color;
}

/**
 * Computes busyness from current count + capacity
 * 
 * @param {Object[]} currCount - Number of people at a location each hour
 * @param {Number} capacity - Max number of people that can be in a location
 * @returns {[string, string]} - Returns a size 2 array where the first string is the status name and 
 *                               the second string is the color the circle should be.
 */
function computeBusyness(currCount, capacity) {
  const intervalSize = Math.max(1, Math.floor(capacity / 4));
  console.log("capcity: ", capacity);
  console.log("num peeps: ", currCount.predicted);

  const bucket = Math.floor(currCount.predicted / intervalSize);
  console.log("bucket: ", bucket);
  switch (bucket) {
    case 0: return ["Empty", "green"];
    case 1: return ["Not Busy", "lightgreen"];
    case 2: return ["Somewhat Busy", "yellow"];
    case 3: return ["Busy", "orange"];
    default: return ["Full", "red"];
  }
}

document.addEventListener("DOMContentLoaded", async function () {
  const titleElement = document.getElementById("title");
  const graphName = titleElement.textContent.trim();

  const validLocations = ["cafeteria", "north_tower_gym", "subway"];
  const dbName = graphName.toLowerCase().replaceAll(" ", "_");

  if (!validLocations.includes(dbName)) {
    console.warn("No data available for this page:", graphName);
    return;
  }

  async function refreshLiveBusyness() {
    try {
      const {hours, openTime, closeTime, openTime2, closeTime2} = await getHours(graphName);
      if (!hours || hours.length === 0) return;
      const currIndex = findCurrTimeIndex(hours, openTime, closeTime, openTime2, closeTime2);

      // closed
      if (currIndex === -1) {
        setBusynessUI("Closed", "grey");
        return;
      }

      let actualIndex;

      for (let i=0; i<hours.length; i++) {
        if (hours[i]==currIndex) {
          actualIndex = i;
        }
      }

      const capData = await fetchCapacity(dbName);
      if (!capData || capData.error) {
        console.error("Capacity error:", capData?.error);
        return;
      }
      const capacity = Number(capData.max_capacity);

      const hourlyData = await fetchHourlyCounts(dbName, hours);
      console.log("live data: ", hourlyData);
      if (!hourlyData || hourlyData.error) {
        console.error("Hourly data error:", hourlyData?.error);
        return;
      }

      const counts = hourlyData.map(r => Number(r.predicted));

      // safety: if something mismatched, clamp index
      const safeIndex = Math.max(0, Math.min(actualIndex, counts.length - 1));
      const currCount = hourlyData[safeIndex];

      const [label, color] = computeBusyness(currCount, capacity);
      setBusynessUI(label, color);

    } catch (err) {
      console.error("Live busyness refresh failed:", err);
    }
  }

  // run now and then keep updating
  await refreshLiveBusyness();
  setInterval(refreshLiveBusyness, 30000); // every 30 seconds
});