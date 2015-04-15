function generateLineChart(container, ajaxKey, fqdn, timedomain, colorClass, useSIPrefixes) {

    if (typeof(useSIPrefixes) === 'undefined') useSIPrefixes = true;

    var containerSize = d3.select(container).node().getBoundingClientRect();
    var width = containerSize.width;
    var height = containerSize.height;

    var margin = {top: 35, right: 25, bottom: 25, left: 50, legend: 10};

    var svg = d3.select(container).append('svg').attr('height', height).attr('width', width);

    var legend = svg.append('g')
        .attr('transform', 'translate(' + margin.left + ', ' + margin.legend + ')');

    var graph = svg.append('g')
        .attr('transform', 'translate(' + margin.left + ', ' + margin.top + ')');

    var dataset = [];
    var serieCount = 0;
    var dataMax = 0;
    var labels = [];
    var dataMin = 0;

    // Request graph data with ajax
    d3.json('/ajax/' + ajaxKey + '/' + fqdn + '/' + timedomain, function (data) {
        labels = data.labels;
        serieCount = data.dataset[0].series.length;
        dataMin = data.minimalHeight;

        // Convert date/time strings to javascript Date objects
        data.dataset.forEach(function (item, index, array) {

            for (var i = 0; i < serieCount; i++) {
                if (dataset.length < serieCount) {
                    dataset.push([]);
                }
                dataset[i].push({
                    point: item.series[i],
                    date: new Date(item.time.date)
                });
                dataMax = Math.max(dataMax, item.series[i]);
            }

        });
        console.log(dataset);
        update();
    });

    function update() {
        var firstDate = dataset[0][0].date;
        var lastDate = dataset[0][dataset[0].length - 1].date;

        var color = colorClass();

        var x = d3.time.scale()
            .domain([firstDate, lastDate])
            .rangeRound([0, width - margin.left - margin.right]);

        var y = d3.scale.linear()
            .domain([0, Math.max(dataMin, dataMax)])
            .range([height - margin.top - margin.bottom, 0]);

        var xAxis = d3.svg.axis()
            .scale(x)
            .ticks(6)
            .orient('bottom');

        var yAxis = d3.svg.axis()
            .scale(y)
            .orient('left');
        if (useSIPrefixes) {
            yAxis = yAxis.tickFormat(d3.format("s"));
        }
        var itemOffset = 0;
        for (var i = 0; i < serieCount; i++) {
            var legendItem = legend.append('g');

            var textLabel = legendItem.append('text')
                .text(labels[i])
                .attr('x', 10 + itemOffset)
                .attr('y', 12);
            var textWidth = 0;
            textLabel.each(function (d) {
                textWidth = this.getBBox().width;
            });
            var colorLabel = legendItem.append('circle')
                .attr('cx', itemOffset)
                .attr('cy', 7)
                .attr('r', 5)
                .style('fill', color(i));
            itemOffset += textWidth + 20;
        }

        for (i = 0; i < serieCount; i++) {
            graph.append('path')
                .attr('d', d3.svg.line()
                    .interpolate("monotone")
                    .x(function (d) {
                        var xding = x(d.date);
                        return xding;
                    })
                    .y(function (d) {
                        var yding = y(d.point);
                        return yding;
                    })(dataset[i]))
                .attr("class", "line")
                .style("stroke", color(i));
        }

        graph.append('g')
            .attr('class', 'axis x')
            .attr('transform', 'translate(0, ' + (height - margin.top - margin.bottom) + ')')
            .call(xAxis);

        graph.append('g')
            .attr('class', 'axis y')
            .call(yAxis);
    }
}