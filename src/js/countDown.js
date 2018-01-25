(function () {
    'use strict';

    function getTimeRemaining (endtime) {
        var t = Date.parse(endtime) - Date.parse(new Date());
        var seconds = Math.floor((t / 1000) % 60);
        var minutes = Math.floor((t / 1000 / 60) % 60);
        var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
        var days = Math.floor(t / (1000 * 60 * 60 * 24));

        return {
            'total': t,
            'days': days,
            'hours': hours,
            'minutes': minutes,
            'seconds': seconds
        };
    }

    function initializeClock(clock, endtime) {
        var daysSpan = clock.querySelector('.c-countDown__days');
        var hoursSpan = clock.querySelector('.c-countDown__hours');
        var minutesSpan = clock.querySelector('.c-countDown__minutes');
        var secondsSpan = clock.querySelector('.c-countDown__seconds');

        function updateClock() {
            var t = getTimeRemaining(endtime);

            daysSpan.innerHTML = t.days;
            hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
            minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
            secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

            if (t.total <= 0) {
                clearInterval(timeinterval);
                var html = "<h2 class='c-sectionTitle'>Early bird sale has started!</h2>";
                html += "<p>Exciting times at ForgeNet! <strong>The early-bird is live!</strong> Claim your FRG for the incredibly low price of $0.50 per coin, only available during the early-bird sale. Don't miss out!</p>";
                html += "<p><a class='c-button c-button--white' target='_blank' href='https://shop.theforgenetwork.com/'>Enter the FRG Shop</a></p>";
                clock.innerHTML = html;
            }
        }

        updateClock();
        var timeinterval = setInterval(updateClock, 1000);
    }

    var timers = document.querySelectorAll('.c-countDown');
    var deadline = new Date(Date.UTC(2018, 0, 25, 20, 1, 0));

    timers.forEach(function (timer) {
        initializeClock(timer, deadline);
    });
})();