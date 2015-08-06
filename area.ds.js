//Area Chart:
//http://bl.ocks.org/mbostock/3883195

// Remember firebug

//More args like the dataarray, and weekday?
var dia = function(day, divId, data) {
    console.log(day);
    //console.log(divId);
    divId = "#"+divId;
    //console.log(divId);

    var margin = {top: 20, right: 20, bottom: 30, left: 50},
        width = 800 - margin.left - margin.right,
        height = 150 - margin.top - margin.bottom;

    //https://github.com/mbostock/d3/wiki/Time-Formatting
    var parseDate = d3.time.format("%H:%M").parse;

    var x = d3.time.scale()
        .range([0, width]);

    var y = d3.scale.linear()
        .range([height, 0]);

    var xAxis = d3.svg.axis()
        .tickFormat(d3.time.format('%H:%M'))
        .ticks(12)
        .scale(x)
        .orient("bottom");

    var yAxis = d3.svg.axis()
        .ticks(2)
        .scale(y)
        .orient("left");

    var area = d3.svg.area()
        .x(function(d) { return x(d.time); })
        .y0(height)
        .y1(function(d) { return y(d.output); });

    //var svg = d3.select("body")
    var svg = d3.select(divId)
        .append("svg")
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");


    data.forEach(function(d) {
        d.time = parseDate(d.time);
        d.output = +d.output;
    });

    x.domain(d3.extent(data, function(d) { return d.time; }));
    y.domain([0, d3.max(data, function(d) { return d.output; })]);

    svg.append("path")
        .datum(data)
        .attr("class", "area")
        .attr("d", area);

    svg.append("g")
        .attr("class", "x axis")
        .attr("transform", "translate(0," + height + ")")
        .call(xAxis);

    svg.append("g")
        .attr("class", "y axis")
        .call(yAxis)
        .append("text")
        .attr("transform", "rotate(-90)")
        .attr("y", 6)
        .attr("dy", ".71em")
        .style("text-anchor", "end")
        .text(day+" - On/Off (%)");
}
