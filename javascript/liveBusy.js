import { getHours, findCurrTimeIndex } from './chronos.js';

// fetch max capacity
async function fetchCapacity(dbName) {
  const res = await fetch(`/Campus_Tracker/php/get_capacity.php?location=${dbName}&x=${Date.now()}`, { cache: "no-store" });
  return await res.json();
}

// fetch per-hour bucketed counts (same length/order as hours[])
async function fetchHourlyCounts(dbName, hours) {
  const hoursParam = encodeURIComponent(hours.join("|"));
  const url = `/Campus_Tracker/php/get_hourly_data.php?location=${dbName}&hours=${hoursParam}&x=${Date.now()}`;
  const res = await fetch(url, { cache: "no-store" });
  return await res.json();
}

// Updates the UI circle + label
function setBusynessUI(label, color) {
  const levelName = document.getElementById("current-level");
  const levelCircle = document.getElementById("circle");
  levelName.textContent = label;
  levelCircle.style.backgroundColor = color;
}

// Computes busyness from current count + capacity
function computeBusyness(currCount, capacity) {
  const intervalSize = Math.max(1, Math.floor(capacity / 4));

  const bucket = Math.floor(currCount / intervalSize);
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
      console.log(hourlyData);
      if (!hourlyData || hourlyData.error) {
        console.error("Hourly data error:", hourlyData?.error);
        return;
      }

      const counts = hourlyData.map(r => Number(r.count));

      // safety: if something mismatched, clamp index
      const safeIndex = Math.max(0, Math.min(actualIndex, counts.length - 1));
      console.log(counts)
      const currCount = counts[safeIndex];

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