
// Code ported from:
// https://github.com/jsiei97/FunTechHouse_WeekTimer_Nexa

// Misc info:
// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Introduction_to_Object-Oriented_JavaScript

// ----------------------------------------------------------
// --- WeekTimerLine  ---------------------------------------
// ----------------------------------------------------------

var WeekTimerLine = function () {
    this.t1_dow = 0;
    this.t1_h   = 0;
    this.t1_m   = 0;

    this.t2_dow = 0;
    this.t2_h   = 0;
    this.t2_m   = 0;
};


WeekTimerLine.prototype.setLine = function (line) {
    //console.log(line);

    var res = line.match('([0-9]{1,1}):([0-9]{1,2}):([0-9]{1,2})-([0-9]{1,1}):([0-9]{1,2}):([0-9]{1,2})');
    //console.log(res);

    if(res == null) {
        console.log("setLine error null:"+line);
        return false;
    }
    if(res.length !== 7) {
        console.log("setLine error line:"+line);
        return false;
    }

    this.t1_dow = parseInt(res[1]);
    this.t1_h   = parseInt(res[2]);
    this.t1_m   = parseInt(res[3]);

    this.t2_dow = parseInt(res[4]);
    this.t2_h   = parseInt(res[5]);
    this.t2_m   = parseInt(res[6]);

    return true;
};

WeekTimerLine.prototype.timeSinceWeekStart = function (d, h, m) {
    var time = 0;

    time += d*24*60;
    time += h*60;
    time += m;

    return time;
};

WeekTimerLine.prototype.isON = function (day, hour, min) {

    //If there is no wildcards
    if( ( this.t1_dow != 0 && this.t1_dow <= 7 ) && ( this.t2_dow != 0 && this.t2_dow <= 7 ) ) {
        var t1  = this.timeSinceWeekStart( this.t1_dow, this.t1_h, this.t1_m );
        var t2  = this.timeSinceWeekStart( this.t2_dow, this.t2_h, this.t2_m );
        var now = this.timeSinceWeekStart( day,    hour, min );

        if(t1<t2) {
            if (t1 <= now  && now < t2) {
                return true;
            }
        } else {
            if (now >= t1 || now < t2) {
                return true;
            }

        }
        return false;
    }

    //dow 0, all days
    if( ( this.t1_dow == 0 ) && ( this.t2_dow == 0 ) ) {
        var t1  = this.timeSinceWeekStart( 0, this.t1_h, this.t1_m );
        var t2  = this.timeSinceWeekStart( 0, this.t2_h, this.t2_m );
        var now = this.timeSinceWeekStart( 0, hour, min );

        if(t1<t2) {
            if (t1 <= now  && now < t2) {
                return true;
            }
        } else {
            if (now >= t1 || now < t2) {
                return true;
            }
        }
        return false;
    }

    //dow 8, all weekday (and sometimes a little bit into the day after).
    if( ( this.t1_dow == 8 ) && ( this.t2_dow == 8 ) ) {
        var t1  = this.timeSinceWeekStart( 0, this.t1_h, this.t1_m );
        var t2  = this.timeSinceWeekStart( 0, this.t2_h, this.t2_m );
        var now = this.timeSinceWeekStart( 0, hour, min );

        if(t1<t2) {
            if(day <= 5) {
                if (t1 <= now  && now < t2) {
                    return true;
                }
            }
            return false;
        } else {
            if(day == 1) {
                if (now >= t1) {
                    return true;
                }
            } else if(day <= 5) {
                if (now >= t1 || now < t2) {
                    return true;
                }
            } else if(day == 6) {
                if (now < t2) {
                    return true;
                }
            }
            return false;
        }
    }

    //dow 9, all weekends (and sometimes a little bit into the day after).
    if( ( this.t1_dow == 9 ) && ( this.t2_dow == 9 ) ) {
        var t1  = this.timeSinceWeekStart( 0, this.t1_h, this.t1_m );
        var t2  = this.timeSinceWeekStart( 0, this.t2_h, this.t2_m );
        var now = this.timeSinceWeekStart( 0, hour, min );

        if(t1<t2) {
            if(day >= 6) {
                if (t1 <= now  && now < t2) {
                    return true;
                }
            }
            return false;
        } else {
            if(day >= 6) {
                if (now >= t1 || now < t2) {
                    return true;
                }
            } else if(day == 1) {
                if (now < t2) {
                    return true;
                }
            }
            return false;
        }
    }

    return false;
};

// ----------------------------------------------------------
// --- WeekTimer --------------------------------------------
// ----------------------------------------------------------


var WeekTimer = function () {
    //List with WeekTimerLines aka wtl
    this.timers = new Array();
};

WeekTimer.prototype.addNewTimers = function (line) {
    //console.log(line);

    //Remove last ; if there is no data after it...
    //console.log(line[line.length-1]);
    if(line[line.length-1]==";"){
        line = line.substring(0, line.length - 1);
    }
    //console.log(line);

    var parts = line.split(";");

    var index;
    for	(index = 0; index < parts.length; index++) {
        //console.log("addNewTimers:"+parts[index]);

        var wtl = new WeekTimerLine();
        if(wtl.setLine(parts[index])===true) {
            this.timers.push(wtl);
        }
    }

    return true;
};

// Add a zero if missing
WeekTimer.prototype.zeroPadd = function (num) {
    if(num == undefined) {
        return "00";
    }
    var str = num.toString();
    if(str.length == 1) {
        str = "0"+str;
    }
    return str;
}

WeekTimer.prototype.getTimerString = function () {
    var str = '';
    var index;
    for	(index = 0; index < this.timers.length; index++) {
        str+=this.timers[index].t1_dow.toString();
        str+=':';
        str+=this.zeroPadd(this.timers[index].t1_h)
            str+=':';
        str+=this.zeroPadd(this.timers[index].t1_m)
            str+='-';
        str+=this.timers[index].t2_dow.toString();
        str+=':';
        str+=this.zeroPadd(this.timers[index].t2_h)
            str+=':';
        str+=this.zeroPadd(this.timers[index].t2_m)
            str+=';';
    }
    return str;
};

WeekTimer.prototype.isON = function (day, hour, min) {
    for	(index = 0; index < this.timers.length; index++) {
        if(this.timers[index].isON(day,hour,min)===true) {
            return true;
        }
    }
    return false;
};

WeekTimer.prototype.bool2procent = function (bool) {
    if(bool===true){
        return 100;
    }
    return 0;
}

// Returns a array that fits in with d3js
WeekTimer.prototype.getWeekDayArray = function (day) {
    var wda = new Array();
    var hour;
    var min;

    //Get a start value
    var time = '00:00';
    var lastOut  = this.isON(day,0,0);
    //console.log(time+" - "+lastOut.toString());
    wda.push({time: time, output: this.bool2procent(lastOut)});

    for(hour=0; hour<=23; hour++) {
        for(min=0; min<=59; min++) {
            var out = this.isON(day,hour,min);
            if(out !== lastOut) {
                //console.log(time+" - "+lastOut.toString());
                wda.push({time: time, output: this.bool2procent(lastOut)});

                time = this.zeroPadd(hour);
                time+= ":";
                time+= this.zeroPadd(min);

                //console.log(time+" - "+lastOut.toString());
                wda.push({time: time, output: this.bool2procent(out)});
                lastOut=out;
            } else {
                time = this.zeroPadd(hour);
                time+= ":";
                time+= this.zeroPadd(min);
            }
        }
    }

    //Then create a end value
    var time = '23:59';
    var lastOut  = this.isON(day,23,59);
    //console.log(time+" - "+lastOut.toString());
    wda.push({time: time, output: this.bool2procent(lastOut)});

    //console.log(wda.toString());
    return wda;
};
