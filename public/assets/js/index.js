$(function () {
    "use strict";

    // chart 1

    var ctx = document.getElementById("chart1").getContext("2d");

    var gradientStroke1 = ctx.createLinearGradient(0, 0, 0, 300);
    gradientStroke1.addColorStop(0, "#6078ea");
    gradientStroke1.addColorStop(1, "#17c5ea");

    var gradientStroke2 = ctx.createLinearGradient(0, 0, 0, 300);
    gradientStroke2.addColorStop(0, "#ff8359");
    gradientStroke2.addColorStop(1, "#ffdf40");

    var myChart = new Chart(ctx, {
        type: "bar",
        data: {
            labels: [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
                "Oct",
                "Nov",
                "Dec",
            ],
            datasets: [
                {
                    label: "Sales",
                    data: sales_data,
                    borderColor: gradientStroke1,
                    backgroundColor: gradientStroke1,
                    hoverBackgroundColor: gradientStroke1,
                    pointRadius: 0,
                    fill: false,
                    borderRadius: 20,
                    borderWidth: 0,
                },
                {
                    label: "Purchase",
                    data: purchase_data,
                    borderColor: gradientStroke2,
                    backgroundColor: gradientStroke2,
                    hoverBackgroundColor: gradientStroke2,
                    pointRadius: 0,
                    fill: false,
                    borderRadius: 20,
                    borderWidth: 0,
                },
            ],
        },

        options: {
            maintainAspectRatio: false,
            barPercentage: 0.5,
            categoryPercentage: 0.8,
            plugins: {
                legend: {
                    display: false,
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
        },
    });

    // chart 2

    var ctx = document.getElementById("chart2").getContext("2d");

    var gradientStroke1 = ctx.createLinearGradient(0, 0, 0, 300);
    gradientStroke1.addColorStop(0, "#fc4a1a");
    gradientStroke1.addColorStop(1, "#f7b733");

    var gradientStroke2 = ctx.createLinearGradient(0, 0, 0, 300);
    gradientStroke2.addColorStop(0, "#4776e6");
    gradientStroke2.addColorStop(1, "#8e54e9");

    var gradientStroke3 = ctx.createLinearGradient(0, 0, 0, 300);
    gradientStroke3.addColorStop(0, "#ee0979");
    gradientStroke3.addColorStop(1, "#ff6a00");

    var gradientStroke4 = ctx.createLinearGradient(0, 0, 0, 300);
    gradientStroke4.addColorStop(0, "#42e695");
    gradientStroke4.addColorStop(1, "#3bb2b8");

    var myChart = new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: top_product_names,
            datasets: [
                {
                    backgroundColor: [
                        gradientStroke1,
                        gradientStroke2,
                        gradientStroke3,
                        gradientStroke4,
                    ],
                    hoverBackgroundColor: [
                        gradientStroke1,
                        gradientStroke2,
                        gradientStroke3,
                        gradientStroke4,
                    ],
                    data: top_product_sale_data,
                    borderWidth: [1, 1, 1, 1],
                },
            ],
        },
        options: {
            maintainAspectRatio: false,
            cutout: 82,
            plugins: {
                legend: {
                    display: false,
                },
            },
        },
    });

    // worl map

    jQuery("#geographic-map-2").vectorMap({
        map: "world_mill_en",
        backgroundColor: "transparent",
        borderColor: "#818181",
        borderOpacity: 0.25,
        borderWidth: 1,
        zoomOnScroll: false,
        color: "#009efb",
        regionStyle: {
            initial: {
                fill: "#008cff",
            },
        },
        markerStyle: {
            initial: {
                r: 9,
                fill: "#fff",
                "fill-opacity": 1,
                stroke: "#000",
                "stroke-width": 5,
                "stroke-opacity": 0.4,
            },
        },
        enableZoom: true,
        hoverColor: "#009efb",
        markers: [
            {
                latLng: [21.0, 78.0],
                name: "Lorem Ipsum Dollar",
            },
        ],
        hoverOpacity: null,
        normalizeFunction: "linear",
        scaleColors: ["#b6d6ff", "#005ace"],
        selectedColor: "#c9dfaf",
        selectedRegions: [],
        showTooltip: true,
    });

    // chart 3

    var ctx = document.getElementById("chart3").getContext("2d");

    var gradientStroke1 = ctx.createLinearGradient(0, 0, 0, 300);
    gradientStroke1.addColorStop(0, "#00b09b");
    gradientStroke1.addColorStop(1, "#96c93d");

    var myChart = new Chart(ctx, {
        type: "line",
        data: {
            // labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
            labels: revenue_days,
            datasets: [
                {
                    label: "Product Revenue",
                    data: revenue_days_price,
                    backgroundColor: [gradientStroke1],
                    fill: {
                        target: "origin",
                        above: "rgb(21 202 32 / 15%)", // Area will be red above the origin
                        //below: 'rgb(21 202 32 / 100%)'   // And blue below the origin
                    },
                    tension: 0.4,
                    borderColor: [gradientStroke1],
                    borderWidth: 3,
                },
            ],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
        },
    });

    // chart 4

    var ctx = document.getElementById("chart4").getContext("2d");

    var gradientStroke1 = ctx.createLinearGradient(0, 0, 0, 300);
    gradientStroke1.addColorStop(0, "#ee0979");
    gradientStroke1.addColorStop(1, "#ff6a00");

    var gradientStroke2 = ctx.createLinearGradient(0, 0, 0, 300);
    gradientStroke2.addColorStop(0, "#283c86");
    gradientStroke2.addColorStop(1, "#39bd3c");

    var gradientStroke3 = ctx.createLinearGradient(0, 0, 0, 300);
    gradientStroke3.addColorStop(0, "#7f00ff");
    gradientStroke3.addColorStop(1, "#e100ff");

    var myChart = new Chart(ctx, {
        type: "pie",
        data: {
            labels: ["Received", "Pending", "Ordered"],
            datasets: [
                {
                    backgroundColor: [
                        gradientStroke2,
                        gradientStroke1,
                        gradientStroke3,
                    ],

                    hoverBackgroundColor: [
                        gradientStroke1,
                        gradientStroke2,
                        gradientStroke3,
                    ],

                    data: order_summary_data,
                    borderWidth: [1, 1, 1],
                },
            ],
        },
        options: {
            maintainAspectRatio: false,
            cutout: 95,
            plugins: {
                legend: {
                    display: false,
                },
            },
        },
    });

    // chart 5

    // var gradientStroke1 = ctx.createLinearGradient(0, 0, 0, 300);
    // gradientStroke1.addColorStop(0, "#f54ea2");
    // gradientStroke1.addColorStop(1, "#ff7676");

    // var gradientStroke2 = ctx.createLinearGradient(0, 0, 0, 300);
    // gradientStroke2.addColorStop(0, "#42e695");
    // gradientStroke2.addColorStop(1, "#3bb2b8");

    var myChart = new Chart(top_cat_ctx, {
        type: "bar",
        data: {
            labels: [1, 2, 3, 4, 5],
            datasets: top_selling_category_database,
        },
        options: {
            maintainAspectRatio: false,
            barPercentage: 0.5,
            categoryPercentage: 0.8,
            plugins: {
                legend: {
                    display: false,
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
        },
    });
});
