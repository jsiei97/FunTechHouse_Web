<?php
$path = "./";

require_once($path."class.page.php");
$page = new page($path, "WeekTimer", true);

//require_once($path."class.html.php");
//$html = new html();

$name = filter_input( INPUT_GET, WEEKTIMER, FILTER_SANITIZE_STRING );
$topicFrom = $page->weekTimers[$name];
if(empty($topicFrom)){
    die("<!-- Error: bad input WEEKTIMER...-->");
}
$topicTo = $topicFrom."_ctrl";

?>
    <script src="mqttws31.js" type="text/javascript"></script>
    <script src="jquery.min.js" type="text/javascript"></script>
    <script src="config.js" type="text/javascript"></script>

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
        mqtt.subscribe(topic, {qos: 0});
        $('#topic').val(topic);
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
            //and check for valid data before update
            $("#timerData").val(payload);
        }
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
        $("#btUpdate").click(function(){
            console.log("My print: Button Update pressed...");
            mqtt.send(myTopicTo, $("#timerData").val() );
            });
        });


    </script>
<?php
$page->printHead();

print $page->h1("WeekTimer, ".$name);
?>

<div>Subscribed to <input type='text' id='topic' disabled />
Status: <input type='text' id='status' size="80" disabled /></div>

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
<input type="text" id="timerData" value="" size="120">
<button id="btUpdate">Update</button> 
</p>

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


<ul id='ws' style="font-family: 'Courier New', Courier, monospace;"></ul>



