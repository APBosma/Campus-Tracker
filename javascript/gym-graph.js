/* 
Was getting my toe nails painted the other day when I looked up charts with JS and found W3 showing Chart.js 
(https://www.w3schools.com/ai/ai_chartjs.asp) and looked at their syntax for the bar graph. I was also using W3 for array 
syntax and had to look at W3 for for loops as well (https://www.w3schools.com/js/js_loop_for.asp). It then just wasn't displaying so I asked
Zach. He reminded me that F12 is my friend and that helped a lot for debugging. Then I asked Professor Bowe and he just glanced at it
which automatically fixed everything. I didn't change any code, he just looked at it.
To hide the Y-axis values I looked up "chart js hide y axis labels" and the AI showed me how to set the tick display to false
*/

// This DOMContentLoaded function thing makes program wait until the HTML and stuff is done being setup before it runs the JS
// EVERYTHING WILL BREAK WITHOUT THIS! DO NOT DELETE!
document.addEventListener("DOMContentLoaded", function () {


    // Grabs the graph
    const gymGraph = document.getElementById('gym-graph');

    const d = new Date(); // Gets current date

    switch(d.getDay()) { // Gets day of the week, found this at https://www.w3schools.com/jsref/jsref_getday.asp
        case 0:
            // Sunday
            var hours = ["4 pm", "5 pm", "6 pm", "7 pm", "8 pm", "9 pm", "10 pm"];
            break;
        case 1:
            // Monday
            var hours = ["8 am", "9 am", "10 am", "11 am", "12 pm", "1 pm", "2 pm", "3 pm", "4 pm", "5 pm", "6 pm", "7 pm", "8 pm", "9 pm", "10 pm"];
            break;
        case 2:
            // Tuesday
            var hours = ["8 am", "9 am", "10 am", "11 am", "12 pm", "1 pm", "2 pm", "3 pm", "4 pm", "5 pm", "6 pm", "7 pm", "8 pm", "9 pm", "10 pm"];
            break;
        case 3:
            // Wednesday
            var hours = ["8 am", "9 am", "10 am", "11 am", "12 pm", "1 pm", "2 pm", "3 pm", "4 pm", "5 pm", "6 pm", "7 pm", "8 pm", "9 pm", "10 pm"];
            break;
        case 4:
            // Thursday
            var hours = ["8 am", "9 am", "10 am", "11 am", "12 pm", "1 pm", "2 pm", "3 pm", "4 pm", "5 pm", "6 pm", "7 pm", "8 pm", "9 pm", "10 pm"];
            break;
        case 5:
            // Friday
            var hours = ["8 am", "9 am", "10 am", "11 am", "12 pm", "1 pm", "2 pm", "3 pm", "4 pm"];
            break;
        case 6:
            // Satuday
            var hours = ["4 pm", "5 pm", "6 pm", "7 pm", "8 pm", "9 pm", "10 pm"];
            break;
    }

    // Sets random numbers to the data
    let theData = new Array(hours.length);
    for (let i=0; i< hours.length; i++) {
        theData[i] = i % 6;
    }

    // Makes bar chart for the gym page
    new Chart(gymGraph, {
        type: 'bar',
        data: {
            labels: hours,
            datasets: [{
                label: "North Towers Gym",
                data: theData
            }]
        },
        options: {
            plugins: {
                legend: {display: false}
            },
            scales: {
                y: {
                    ticks: {display: false},
                    beginAtZero: true
                }
            }
        }
    });

});

