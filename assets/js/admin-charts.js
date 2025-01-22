// Chart.js configuration
Chart.defaults.font = {
    family: "'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,\"Segoe UI\",Roboto,\"Helvetica Neue\",Arial,sans-serif'",
    size: 12,
    color: '#858796'
};

function initializeCharts(userData, courseData) {
    // Common chart options
    const commonOptions = {
        maintainAspectRatio: false,
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: window.innerWidth < 768 ? 'bottom' : 'right',
                labels: {
                    boxWidth: window.innerWidth < 768 ? 10 : 12,
                    padding: window.innerWidth < 768 ? 10 : 15,
                    font: {
                        size: window.innerWidth < 768 ? 10 : 12
                    }
                }
            },
            tooltip: {
                backgroundColor: "rgb(255,255,255)",
                bodyColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                padding: {
                    x: window.innerWidth < 768 ? 10 : 15,
                    y: window.innerWidth < 768 ? 10 : 15
                },
                displayColors: false,
                caretPadding: 10,
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': ' + context.raw;
                    }
                }
            }
        }
    };

    // Function to handle resize events
    const handleResize = () => {
        const legendPosition = window.innerWidth < 768 ? 'bottom' : 'right';
        const fontSize = window.innerWidth < 768 ? 10 : 12;
        const padding = window.innerWidth < 768 ? 10 : 15;

        [userGrowthChart, courseDistributionChart].forEach(chart => {
            if (chart) {
                chart.options.plugins.legend.position = legendPosition;
                chart.options.plugins.legend.labels.font.size = fontSize;
                chart.options.plugins.legend.labels.padding = padding;
                chart.update();
            }
        });
    };

    // Add resize event listener
    window.addEventListener('resize', handleResize);

    // User Growth Chart
    const userGrowthCtx = document.getElementById('userGrowthChart')?.getContext('2d');
    if (!userGrowthCtx) return;

    const userGrowthChart = new Chart(userGrowthCtx, {
        type: 'line',
        data: {
            labels: ['Jan 1', 'Jan 7', 'Jan 14', 'Jan 21', 'Jan 28', 'Jan 31'],
            datasets: [{
                label: 'New Users (January 2025)',
                data: userData.data || [0, 0, 0, 0, 0, 0],
                tension: 0.3,
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointRadius: window.innerWidth < 768 ? 2 : 3,
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: 'rgba(78, 115, 223, 1)',
                pointHoverRadius: window.innerWidth < 768 ? 3 : 5,
                pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                pointHitRadius: window.innerWidth < 768 ? 8 : 10,
                pointBorderWidth: 2,
                fill: true
            }]
        },
        options: {
            ...commonOptions,
            scales: {
                x: {
                    grid: {
                        display: true,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: window.innerWidth < 768 ? 4 : 7,
                        maxRotation: window.innerWidth < 768 ? 45 : 0
                    }
                },
                y: {
                    ticks: {
                        maxTicksLimit: window.innerWidth < 768 ? 5 : 7,
                        padding: 10
                    },
                    grid: {
                        color: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }
            }
        }
    });

    // User Distribution Chart
    const userDistributionCtx = document.getElementById('userDistributionChart')?.getContext('2d');
    if (!userDistributionCtx) return;

    new Chart(userDistributionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Students', 'Teachers', 'Admins'],
            datasets: [{
                data: [userData.students, userData.teachers, userData.admins],
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }]
        },
        options: {
            ...commonOptions,
            cutout: '70%',
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        padding: 10,
                        boxWidth: 12
                    }
                }
            }
        }
    });

    // Course Distribution Chart
    const courseDistributionCtx = document.getElementById('courseDistributionChart')?.getContext('2d');
    if (!courseDistributionCtx) return;

    const courseDistributionChart = new Chart(courseDistributionCtx, {
        type: 'doughnut',
        data: {
            labels: courseData?.labels || [],
            datasets: [{
                data: courseData?.data || [],
                backgroundColor: [
                    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                    '#858796', '#5a5c69', '#2e59d9', '#17a673', '#2c9faf'
                ],
                hoverBackgroundColor: [
                    '#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617',
                    '#60616f', '#373840', '#1e3a8a', '#0f694a', '#1b6b75'
                ],
                hoverBorderColor: "rgba(234, 236, 244, 1)"
            }]
        },
        options: {
            ...commonOptions,
            cutout: window.innerWidth < 768 ? '65%' : '75%'
        }
    });
}

function initializeStatCounters(statsData) {
    if (!statsData) return;

    const counterOptions = {
        duration: 1000,
        useGrouping: true
    };

    const elements = {
        totalUsers: document.getElementById('totalUsersCounter'),
        activeCourses: document.getElementById('activeCoursesCounter'),
        totalEnrollments: document.getElementById('totalEnrollmentsCounter'),
        revenue: document.getElementById('revenueCounter')
    };

    // Initialize counters
    Object.entries(elements).forEach(([key, element]) => {
        if (element && statsData[key]) {
            let value = statsData[key];
            if (key === 'revenue') {
                element.textContent = new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD'
                }).format(value);
            } else {
                element.textContent = new Intl.NumberFormat('en-US').format(value);
            }
        }
    });
}
