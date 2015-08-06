<?php
$path = "./";

require_once($path."class.page.php");
$page = new page($path, "WeekTimer", true);

require_once($path."class.html.php");
$html = new html();

$name = filter_input( INPUT_GET, WEEKTIMER, FILTER_SANITIZE_STRING );
$topicFrom = $page->weekTimers[$name];
if(empty($topicFrom)){
    die("<!-- Error: bad input WEEKTIMER...-->");
}
$topicTo = $topicFrom."_ctrl";

?>
<!-- tmp d3 svg css rules -->
<style>
.areaDia {
    font: 10px sans-serif;
}

.axis path,
    .axis line {
        fill: none;
        stroke: #000;
        shape-rendering: crispEdges;
}

.area {
    fill: steelblue;
}
</style>

    <script src="mqttws31.js" type="text/javascript"></script>
    <script src="jquery.min.js" type="text/javascript"></script>
    <script src="config.js" type="text/javascript"></script>

    <script src="WeekTimer.js" type="text/javascript"></script>
    <script src="d3.js"        type="text/javascript"></script>
    <script src="area.ds.js"   type="text/javascript"></script>

    <script type="text/javascript">

<?php
/*
echo "<pre>";
echo $name."\n";
echo $topicFrom."\n";
echo $topicTo."\n";
var_dump($page->weekTimers);
var_dump($page->weekTimers[$name]);
echo "</pre>";
*/
echo "var myTopicTo   = '".$topicTo."';\n";
echo "var myTopicFrom = '".$topicFrom."';\n";
?>

    var wt = new WeekTimer();

    var mqtt;
    var reconnectTimeout = 2000;

    function MQTTconnect() {
        mqtt = new Paho.MQTT.Client(
                        host,
                        port,
                        "web_" + parseInt(Math.random() * 100,
                        10));
        var options = {
            timeout: 3,
            useSSL: useTLS,
            cleanSession: cleansession,
            onSuccess: onConnect,
            onFailure: function (message) {
                $('#status').val("Connection failed: " + message.errorMessage + "Retrying");
                setTimeout(MQTTconnect, reconnectTimeout);
            }
        };

        mqtt.onConnectionLost = onConnectionLost;
        mqtt.onMessageArrived = onMessageArrived;

        if (username != null) {
            options.userName = username;
            options.password = password;
        }
        console.log("Host="+ host + ", port=" + port + " TLS = " + useTLS + " username=" + username + " password=" + password);
        mqtt.connect(options);
    }

    function onConnect() {
        $('#status').val('Connected to ' + host + ':' + port);
        // Connection succeeded; subscribe to our topic
        mqtt.subscribe(myTopicTo,   {qos: 0});
        mqtt.subscribe(myTopicFrom, {qos: 0});
        $('#topic').val(myTopicFrom);

        // always start with a new sync
        mqtt.send(myTopicTo, "status");
    }

    function onConnectionLost(response) {
        setTimeout(MQTTconnect, reconnectTimeout);
        $('#status').val("connection lost: " + responseObject.errorMessage + ". Reconnecting");

    };

    function onMessageArrived(message) {

        var topic = message.destinationName;
        var payload = message.payloadString;

        $('#ws').prepend('<li>' + topic + ' = ' + payload + '</li>');

        if(topic === myTopicFrom){
            if(/^force/.test(payload)){
                console.log("My print: regexp "+payload);
                $('#timerStatus').val(payload);
            } else {
                //TODO: check for valid data before update
                $("#timerData").val(payload);
                updateDiagrams(payload);
            }
        }
    };

    function updateDiagrams(timerData){
        console.log("updateDiagrams timerData: "+timerData);
        wt.addNewTimers(timerData);
        dia("Mon", "diagramMon", wt.getWeekDayArray(1));
        dia("Tue", "diagramTue", wt.getWeekDayArray(2));
        dia("Wed", "diagramWed", wt.getWeekDayArray(3));
        dia("Thu", "diagramThu", wt.getWeekDayArray(4));
        dia("Fri", "diagramFri", wt.getWeekDayArray(5));
        dia("Sat", "diagramSat", wt.getWeekDayArray(6));
        dia("Sun", "diagramSun", wt.getWeekDayArray(7));
    };

    $(document).ready(function() {
        MQTTconnect();
    });

    $(document).ready(function(){
        $("#btForceOn").click(function(){
            console.log("My print: Button ON pressed...");
            mqtt.send(myTopicTo, "force ON " + $("#forceONTime").val() );
            });
        });

    $(document).ready(function(){
        $("#btForceOFF").click(function(){
            console.log("My print: Button OFF pressed...");
            mqtt.send(myTopicTo, "force OFF " + $("#forceOFFTime").val() );
            });
        });

    $(document).ready(function(){
        $("#btAuto").click(function(){
            console.log("My print: Button Auto pressed...");
            mqtt.send(myTopicTo, "force AUTO 0");
            });
        });

    $(document).ready(function(){
        $("#btStatus").click(function(){
            console.log("My print: Button Sync pressed...");
            mqtt.send(myTopicTo, "status");
            });
        });

    $(document).ready(function(){
        $("#btSave").click(function(){
            console.log("My print: Button Update pressed...");
            mqtt.send(myTopicTo, $("#timerData").val() );
            });
        });

    $(document).ready(function(){
        $("#btApply").click(function(){
            console.log("My print: Button Apply pressed...");

            var timer = $("#timerData").val() + ";" +
                $("#dowBegin").val() + ":" +
                $("#hhBegin").val() + ":" +
                $("#mmBegin").val() + "-" +
                $("#dowEnd").val() + ":" +
                $("#hhEnd").val() + ":" +
                $("#mmEnd").val();

            $("#timerData").val(timer);
            updateDiagrams(timer);
            });
        });

    </script>
<?php
$page->printHead();

print $page->h1("WeekTimer, ".$name);
?>

<div>
Subscribed to <input type='text' id='topic'  size="60" disabled /><br>
Status: <input type='text' id='status' size="80" disabled /><br>
TimerStatus: <input type='text' id='timerStatus' size="80" disabled />
</div>

<?php
print $page->h2("Force");
?>
<p>
<button id="btForceOn">Force ON</button>
<input type="text" id="forceONTime" value="60" size="3">
<button id="btForceOFF">Force OFF</button>
<input type="text" id="forceOFFTime" value="60" size="3">
<button id="btAuto">Auto</button>
</p>

<p>
Time is in minutes, and 0 time is forever.
</p>
<p>
If set to forced on for 60 minutes, it will be forces on for 1 hour and then reverted back to auto after that time.
</p>

<?php
print $page->h2("TimerData");
?>
<p>
<button id="btStatus">Sync</button>
<input type="text" id="timerData" value="" size="100">
<button id="btSave">Save</button>
</p>


<p>
<?php
print $html->select("dowBegin", "1",
    $html->option("0", "", "All days").
    $html->option("8", "", "Weekdays").
    $html->option("9", "", "Weekends").
    $html->option("1", "", "Mon").
    $html->option("2", "", "Tue").
    $html->option("3", "", "Wed").
    $html->option("4", "", "Thu").
    $html->option("5", "", "Fri").
    $html->option("6", "", "Sat").
    $html->option("7", "", "Sun")
);
print "&nbsp:&nbsp";

$hh="";
for($i=0;$i<=23;$i++){
    $hh.=$html->option(sprintf("%02d",$i), "", sprintf("%02d",$i));
}
print $html->select("hhBegin", "1", $hh);
print "&nbsp:&nbsp";

print $html->inputText("mmBegin", "00", "2", "2");
print "&nbsp-&nbsp";

print $html->select("dowEnd", "1",
    $html->option("0", "", "All days").
    $html->option("8", "", "Weekdays").
    $html->option("9", "", "Weekends").
    $html->option("1", "", "Mon").
    $html->option("2", "", "Tue").
    $html->option("3", "", "Wed").
    $html->option("4", "", "Thu").
    $html->option("5", "", "Fri").
    $html->option("6", "", "Sat").
    $html->option("7", "", "Sun")
);
print "&nbsp:&nbsp";

print $html->select("hhEnd", "1", $hh);
print "&nbsp:&nbsp";

print $html->inputText("mmEnd", "00", "2", "2");
?>
<button id="btApply">Apply</button>
</p>

<div class="areaDia" id="diagramMon"></div>
<div class="areaDia" id="diagramTue"></div>
<div class="areaDia" id="diagramWed"></div>
<div class="areaDia" id="diagramThu"></div>
<div class="areaDia" id="diagramFri"></div>
<div class="areaDia" id="diagramSat"></div>
<div class="areaDia" id="diagramSun"></div>

<p>
The timerdata is built with [start]-[stop] and then a ; to mart the next [start]-[stop] time.
</p>
<p>
Syntax: D:HH:MM-D:HH:MM;D:HH:MM-D:HH:MM;...
</p>
<p>
Where D is day of week, where 1 is monday and 7 is sunday.
</p>
<p>
Wildcards is 0 means all days, 8 weekdays (monday to friday), 9 weekends (saturday and sunday).
</p>
<p>
Where HH is hours in 24h mode, 00 to 23.
</p>
<p>
Where MM is minutes, 00 to 59.
</p>

<?php
print $page->h2("MQTT status");
?>
<ul id='ws' style="font-family: 'Courier New', Courier, monospace;"></ul>



