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
*/

function getTime(location) {
    const d = new Date();
    
    switch(location) {
        case "North Tower Gym":
            switch(d.getDay()) { // Gets day of the week, found this at https://www.w3schools.com/jsref/jsref_getday.asp
                case 0:
                    // Sunday
                    return ["4 pm", "5 pm", "6 pm", "7 pm", "8 pm", "9 pm", "10 pm"];
                case 1:
                    // Monday
                    return ["8 am", "9 am", "10 am", "11 am", "12 pm", "1 pm", "2 pm", "3 pm", "4 pm", "5 pm", "6 pm", "7 pm", "8 pm", "9 pm", "10 pm"];
                case 2:
                    // Tuesday
                    return ["8 am", "9 am", "10 am", "11 am", "12 pm", "1 pm", "2 pm", "3 pm", "4 pm", "5 pm", "6 pm", "7 pm", "8 pm", "9 pm", "10 pm"];
                case 3:
                    // Wednesday
                    return ["8 am", "9 am", "10 am", "11 am", "12 pm", "1 pm", "2 pm", "3 pm", "4 pm", "5 pm", "6 pm", "7 pm", "8 pm", "9 pm", "10 pm"];
                case 4:
                    // Thursday
                    return ["8 am", "9 am", "10 am", "11 am", "12 pm", "1 pm", "2 pm", "3 pm", "4 pm", "5 pm", "6 pm", "7 pm", "8 pm", "9 pm", "10 pm"];
                case 5:
                    // Friday
                    return ["8 am", "9 am", "10 am", "11 am", "12 pm", "1 pm", "2 pm", "3 pm", "4 pm"];
                case 6:
                    // Satuday
                    return ["4 pm", "5 pm", "6 pm", "7 pm", "8 pm", "9 pm", "10 pm"];
            }
        case "Cafeteria":
            switch(d.getDay()) {
                case 0:
                    // Sunday
                    return ["10 am", "11 am", "12 pm", "1 pm", "4 pm", "5 pm", "6 pm"];
                case 1:
                    // Monday
                    return ["7 am", "8 am", "9 am", "10 am", "11 am", "12 pm", "1 pm", "2 pm", "3 pm", "4 pm", "5 pm", "6 pm"];
                case 2:
                    // Tuesday
                    return ["7 am", "8 am", "9 am", "10 am", "11 am", "12 pm", "1 pm", "2 pm", "3 pm", "4 pm", "5 pm", "6 pm"];
                case 3:
                    // Wednesday
                    return ["7 am", "8 am", "9 am", "10 am", "11 am", "12 pm", "1 pm", "2 pm", "3 pm", "4 pm", "5 pm", "6 pm"];
                case 4:
                    // Thursday
                    return ["7 am", "8 am", "9 am", "10 am", "11 am", "12 pm", "1 pm", "2 pm", "3 pm", "4 pm", "5 pm", "6 pm"];
                case 5:
                    // Friday
                    return ["7 am", "8 am", "9 am", "10 am", "11 am", "12 pm", "1 pm", "2 pm", "3 pm", "4 pm", "5 pm"];
                case 6:
                    // Satuday
                    return ["10 am", "11 am", "12 pm", "1 pm", "4 pm", "5 pm"];
            }
        case "Subway":
            switch(d.getDay()) {
                case 0:
                    // Sunday
                    return ["5 pm", "6 pm", "7 pm", "8 pm", "9 pm", "10 pm"];
                case 1:
                    // Monday
                    return ["7 am", "8 am", "9 am", "10 am", "11 am", "12 pm", "1 pm", "2 pm", "3 pm", "4 pm", "5 pm", "6 pm", "7 pm", "8 pm", "9 pm", "10 pm"];
                case 2:
                    // Tuesday
                    return ["7 am", "8 am", "9 am", "10 am", "11 am", "12 pm", "1 pm", "2 pm", "3 pm", "4 pm", "5 pm", "6 pm", "7 pm", "8 pm", "9 pm", "10 pm"];
                case 3:
                    // Wednesday
                    return ["7 am", "8 am", "9 am", "10 am", "11 am", "12 pm", "1 pm", "2 pm", "3 pm", "4 pm", "5 pm", "6 pm", "7 pm", "8 pm", "9 pm", "10 pm"];
                case 4:
                    // Thursday
                    return ["7 am", "8 am", "9 am", "10 am", "11 am", "12 pm", "1 pm", "2 pm", "3 pm", "4 pm", "5 pm", "6 pm", "7 pm", "8 pm", "9 pm", "10 pm"];
                case 5:
                    // Friday
                    return ["7 am", "8 am", "9 am", "10 am", "11 am", "12 pm", "1 pm", "2 pm", "3 pm", "4 pm", "5 pm", "6 pm", "7 pm", "8 pm", "9 pm", "10 pm"];
                case 6:
                    // Satuday
                    return ["5 pm", "6 pm", "7 pm", "8 pm", "9 pm"];

            }
    }

}

function setBarColors(hours) {
    const d = new Date(); // Gets current date
    let hour = d.getHours(); // Gets the current hour

    // Gets the hour and turns it into a string
    let currTime = "";
    if (hour > 12) {
        currTime = (hour-12).toString() + " pm";
    } else if (hour == 12) {
        currTime = "12 pm";
    } else if (hour == 0) {
        currTime = "12 am";
    } else {
        currTime = hour.toString() + " am";
    }

    // Sets the color of the bars based on the time
    let hitCurrTime = false;
    let barColors = new Array(hours.length);
    for (let i=0; i< hours.length; i++) {
        if (currTime == hours[i]) {
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

function createGraph(name, hours, barColors, data) {
    const graph = document.getElementById('graph');

    new Chart(graph, {
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

// This DOMContentLoaded function thing makes program wait until the HTML and stuff is done being setup before it runs the JS
// EVERYTHING WILL BREAK WITHOUT THIS! DO NOT DELETE!
document.addEventListener("DOMContentLoaded", function () {
    let titleElement = document.getElementById("title")
    let graphName = titleElement.textContent;
    const hours = getTime(graphName);
    const barColors = setBarColors(hours);

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
            createGraph(graphName, hours, barColors, theData);
        })
        .catch(err => {
            console.error("Error loading database data", err);
        });
});
