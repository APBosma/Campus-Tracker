/* 
Was getting my toe nails painted the other day when I looked up charts with JS and found W3 showing Chart.js 
(https://www.w3schools.com/ai/ai_chartjs.asp) and looked at their syntax for the bar graph. I was also using W3 for array 
syntax and had to look at W3 for for loops as well (https://www.w3schools.com/js/js_loop_for.asp). It then just wasn't displaying so I asked
Zach. He reminded me that F12 is my friend and that helped a lot for debugging. Then I asked Professor Bowe and he just glanced at it
which automatically fixed everything. I didn't change any code, he just looked at it.
To hide the Y-axis values I looked up "chart js hide y axis labels" and the AI showed me how to set the tick display to false
Used this to hide gridliens: https://www.reddit.com/r/learnjavascript/comments/x41web/remove_grid_lines_in_bar_chart_chartjs/
Needed to get the current hour so I looked up "How to get current hour javascript" and used this: https://www.w3schools.com/jsref/jsref_gethours.asp
I needed to convert the current hour to string so I looked up "int to string JS" and the google AI told me about .toString()
I wanted to store in the page what page it was so I looked up "JS how to get information from HTML" and the google AI showed me syntax
to get information from the HTML page. I then wanted to hide it on the page so I looked up "html how to hide an element" and saw I could
toggle the display.
I didn't know how to change the words and the circle color for the live busy thing for each page so I looked it up on google. The 
google AI showed me how to use .textContent and .style.backgroundColor for this.
*/
import {getHours, findCurrTimeIndex} from './chronos.js';

let chart = null;

/**
 * Gets the color each bar should be based on the current time
 * 
 * @param {string[]} hours - The hours the location is open
 * @returns {string[]} - An array with each the color each bar should be
 */
function setBarColors(hours) {
    const currentTime = findCurrTimeIndex(hours); // Gets the index of the time current time in hours, else returns -1

    // Sets the color of the bars based on the time
    let hitCurrTime = false;
    let barColors = new Array(hours.length);
    for (let i=0; i< hours.length; i++) {
        if (currentTime == i) {
            hitCurrTime = true;
            barColors[i] = 'rgba(0, 40, 145, 1)'
        } else if (hitCurrTime == false) {
            barColors[i] = 'rgba(0, 40, 145, 0.4)'
        } else {
            barColors[i] = 'rgba(0, 40, 145, 0.7)'
        }
    }
    return barColors;
}

/**
 * Sets the graph up in the location pages
 * 
 * @param {string} name - Name of the location/graph
 * @param {string[]} hours - String array of the hours the location is open (Used for x-axis)
 * @param {string[]} barColors - Color each bar should be (Set with setBarColors())
 * @param {Number[]} data - Number of people at the location for each hour
 */
function createGraph(name, hours, barColors, data) {
    const graph = document.getElementById('graph');

    chart = new Chart(graph, {
        type: 'bar',
        data: {
            labels: hours,
            datasets: [{
                label: name,
                data: data,
                backgroundColor: barColors,
                borderRadius: 100
            }]
        },
        options: {
            plugins: {
                legend: {display: false}
            },
            scales: {
                y: {
                    display: false,
                    ticks: {display: false},
                    beginAtZero: true,
                    grid: {display: false}
                },
                x: {
                    grid: {display: false}
                }
            }
        }
    });
}

/**
 * Updates the graph with newest information
 * 
 * @param {string[]} hours - String array of the hours the location is open
 * @param {string[]} barColors - Color each bar should be (Set with setBarColors())
 * @param {Number[]} data - Number of people at the location for each hour
 */
function updateGraph(hours, barColors, data) {
  if (!chart) return;

  chart.data.labels = hours;
  chart.data.datasets[0].data = data;
  chart.data.datasets[0].backgroundColor = barColors;
  chart.update();
}

async function runSimulationTick() {
  // makes sure simulation keeps writing new rows
  await fetch("/Campus_Tracker/php/simulation.php?x=" + Date.now(), { cache: "no-store" });
}

async function fetchHourlyCounts(dbName, hours) {
  const hoursParam = encodeURIComponent(hours.join("|"));
  const url = `/Campus_Tracker/php/get_hourly_data.php?location=${dbName}&hours=${hoursParam}&x=${Date.now()}`;
  const res = await fetch(url, { cache: "no-store" });
  return await res.json();
}


// This DOMContentLoaded function thing makes program wait until the HTML and stuff is done being setup before it runs the JS
// EVERYTHING WILL BREAK WITHOUT THIS! DO NOT DELETE!
document.addEventListener("DOMContentLoaded", async function () {
    let titleElement = document.getElementById("title")
    let graphName = titleElement.textContent;
    //const hours = await getHours(graphName);
    //const barColors = setBarColors(hours);

    //checks valid locations for fetch
    const validLocations = ["cafeteria", "north_tower_gym", "subway"];
    const dbName = graphName.toLowerCase().replaceAll(" ", "_");

    if (!validLocations.includes(dbName)) {
        console.warn("No data available for this graph:", graphName);
        return;
    }

    // Build hours from DB-defined schedule
  let {hours, openTime, closeTime, openTime2, closeTime2} = await getHours(graphName);
  if (!hours || hours.length === 0) {
    console.warn("No hours returned for:", graphName);
    return;
  }

  async function tickAndRender() {
    try {
        await runSimulationTick();

        const {hours, openTime, closeTime, openTime2, closeTime2} = await getHours(graphName);
        if (!hours || hours.length === 0) return;

        const currIndex = findCurrTimeIndex(hours, openTime, closeTime, openTime2, closeTime2);
        const hoursParam = encodeURIComponent(hours.join("|"));

        // fetch real data
        const dbData = await fetchHourlyCounts(dbName, hours);
        if (!dbData || dbData.error) return;

        // fetch predictions
        const predRes = await fetch(`/Campus_Tracker/php/get_predicted_data.php?location=${dbName}&hours=${hoursParam}&x=${Date.now()}`, { cache: "no-store" });
        const predData = await predRes.json();

        // merge: use real data for past/current hours, predictions for future
        const data = hours.map((_, i) => {
            if (i <= currIndex) {
                return Number(dbData[i]?.count ?? 0);
            } else {
                return Number(predData[i]?.predicted ?? 0);
            }
        });

        // two color arrays: solid for real, faded for predicted
        let beenHit = false;
        const barColors = hours.map((_, i) => {
            if (hours[i] === currIndex) {                                     // current
                beenHit = true;
                return 'rgba(0, 40, 145, 1)';       
            }
            if (!beenHit) return 'rgba(0, 40, 145, 0.4)';                   // past
            return 'rgba(0, 40, 145, 0.2)';                                 // predicted (faded)
        });

        if (!chart) createGraph(graphName, hours, barColors, data);
        else updateGraph(hours, barColors, data);

    } catch (err) {
        console.error("Live update failed:", err);
    }
}

  // run once now
  await tickAndRender();

  // update every 60 seconds (hourly bars don’t need 15s updates; 60s feels “live” without spamming)
  setInterval(tickAndRender, 60000);
});
