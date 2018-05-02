$(document).ready(function () {

    $('a[href^="#"]').click(function() {
        var destino = $(this.hash);
        if (destino.length == 0) {
            destino = $('a[name="' + this.hash.substr(1) + '"]');
        }
        if (destino.length == 0) {
            destino = $('html');
        }
        $('html, body').animate({ scrollTop: destino.offset().top }, 500);
        return false;
    });

    var bar = new ProgressBar.Circle('#cifras-años', {
        color: '#676a6c',
        // This has to be the same size as the maximum width to
        // prevent clipping
        strokeWidth: 4,
        trailWidth: 0,
        easing: 'easeInOut',
        duration: 3000,
        text: {
            autoStyleContainer: false
        },
        svgStyle: {
            width: '80%'
        },
        from: { color: '#007bff', width: 0 },
        to: { color: '#007bff', width: 4 },
        // Set default step function for all animate calls
        step: function(state, circle) {
            circle.path.setAttribute('stroke', state.color);
            circle.path.setAttribute('stroke-width', state.width);

            var value = '<h1><span class="navy">' + Math.round(circle.value() * 20) + ' mil</span></br>usuarios</h1>';
            if (value === 0) {
                circle.setText('');
            } else {
                circle.setText(value);
            }

        }
    });
    var bar2 = new ProgressBar.Circle('#cifras-invertir', {
        color: '#676a6c',
        // This has to be the same size as the maximum width to
        // prevent clipping
        strokeWidth: 4,
        trailWidth: 0,
        easing: 'easeInOut',
        duration: 4000,
        text: {
            autoStyleContainer: false
        },
        svgStyle: {
            width: '80%'
        },
        from: { color: '#007bff', width: 0 },
        to: { color: '#007bff', width: 4 },
        // Set default step function for all animate calls
        step: function(state, circle) {
            circle.path.setAttribute('stroke', state.color);
            circle.path.setAttribute('stroke-width', state.width);

            var value = '<h1><span class="navy">' + Math.round(circle.value() * 40) + ' millones</span></br>de documentos</h1>';
            if (value === 0) {
                circle.setText('');
            } else {
                circle.setText(value);
            }

        }
    });
    var bar3 = new ProgressBar.Circle('#cifras-capital', {
        color: '#676a6c',
        // This has to be the same size as the maximum width to
        // prevent clipping
        strokeWidth: 4,
        trailWidth: 0,
        easing: 'easeInOut',
        duration: 5000,
        text: {
            autoStyleContainer: false
        },
        svgStyle: {
            width: '80%'
        },
        from: { color: '#007bff', width: 0 },
        to: { color: '#007bff', width: 4 },
        // Set default step function for all animate calls
        step: function(state, circle) {
            circle.path.setAttribute('stroke', state.color);
            circle.path.setAttribute('stroke-width', state.width);

            var value = '<h1><span class="navy">' + Math.round(circle.value() * 10) + '</span></br>de nota final</h1>';
            if (value === 0) {
                circle.setText('');
            } else {
                circle.setText(value);
            }

        }
    });
    var waypoint = new Waypoint({
        element: document.getElementById('cifras-años'),
        handler: function(direction) {
            bar.animate(1.0);
        },
        offset: '75%'
    });
    var waypoint2 = new Waypoint({
        element: document.getElementById('cifras-invertir'),
        handler: function(direction) {
            bar2.animate(1.0);
        },
        offset: '75%'
    });
    var waypoint3 = new Waypoint({
        element: document.getElementById('cifras-capital'),
        handler: function(direction) {
            bar3.animate(1.0);
        },
        offset: '75%'
    });


});

new WOW().init();