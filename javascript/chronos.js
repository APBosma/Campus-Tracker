// Needed to get the hour before the colon so I looked up "Javascript how to get string up to character" and used
// this website https://www.geeksforgeeks.org/javascript/javascript-string-substring-method/
// Lol I then looked up "type casting javascript string to int" and found out about parseInt() and realized it would ignore 
// the : and after which was basically just what I needed


function getHours(locationName) {
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

    fetch("/Campus_Tracker/get_hours.php?location=" + locationName + "?day=" + currDay)
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

        if (data.open_time2 && data.close_time2) {
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

        return hours;
    })
    .catch(err => {
        console.error("Error loading database data", err);
    });

}