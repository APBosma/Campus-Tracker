// Needed to get the hour before the colon so I looked up "Javascript how to get string up to character" and used
// this website https://www.geeksforgeeks.org/javascript/javascript-string-substring-method/
// Lol I then looked up "type casting javascript string to int" and found out about parseInt() and realized it would ignore 
// the : and after which was basically just what I needed
// Wasn't sure how to get the import stuff to work so I looked up "Javascript how to import functions from another file" and 
// the google AI showed me a few examples. It took me a few tries because I put default at first but once I added the other function
// I realized I didn't need it.

// Gets hours in an array using the location name and grabbing the day the user is looking
export function getHours(locationName) {
    const d = new Date();
    let currDay = 0;

    switch(d.getDay()) {
        case 0:
            currDay = "sunday";
            break;
        case 1:
            currDay = "monday";
            break;
        case 2:
            currDay = "tuesday";
            break;
        case 3:
            currDay = "wednesday";
            break;
        case 4:
            currDay = "thursday";
            break;
        case 5:
            currDay = "friday";
            break;
        case 6:
            currDay = "saturday";
            break;
    }

    console.log("Sending location:", locationName);
    console.log("Sending day:", currDay);

    //checks valid locations for fetch
    const validLocations = ["cafeteria", "north_tower_gym", "subway"];
    const dbName = locationName.toLowerCase().replaceAll(" ", "_");

    if (!validLocations.includes(dbName)) {
        console.warn("No data available for this graph:", graphName);
        return;
    }


    return fetch("/Campus_Tracker/get_hours.php?location=" + dbName + "&day=" + currDay)
    .then(res => res.json())
    .then(hours => {
        if (!hours || hours.error) {
            console.error("Error from server:", hours?.error);
            return;
        }
        const open = parseInt(hours.open_time1);
        const close = parseInt(hours.close_time1);

        let times = [];
        for (let i = open; i < close; i++) {
            if (i < 12) {
                times.push(i + " am");
            } else if (i == 12) {
                times.push("12 pm");
            } else if (i == 24 ) {
                times.push("12 am");
            } else {
                times.push(i-12 + " pm");
            }
        }

        if (hours.open_time2 && hours.close_time2) {
            const open2 = parseInt(hours.open_time2);
            const close2 = parseInt(hours.close_time2);
            for (let i = open2; i < close2; i++) {
                if (i < 12) {
                    times.push(i + " am");
                } else if (i == 12) {
                    times.push("12 pm");
                } else if (i == 24 ) {
                    times.push("12 am");
                } else {
                    times.push(i-12 + " pm");
                }
            }
        }

        return times;
    })
    .catch(err => {
        console.error("Error loading database data", err);
    });

}

// Gets the index of the current time (Ex. I am writing this at 9 pm, so this would return the index of 9 pm in the array)
// Returns -1 if the time isn't there (Location is closed)
export function findCurrTimeIndex(hours) {
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

    let i = 0;
    for (i; i< hours.length; i++) {
        if (currTime == hours[i]) {
            return i;
        }
    }
    return -1;
}