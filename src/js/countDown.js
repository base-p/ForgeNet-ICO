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
            }
        }

        updateClock();
        var timeinterval = setInterval(updateClock, 1000);
    }

    var timers = document.querySelectorAll('.c-countDown');
    var deadline = new Date('2018-01-27T00:00:00');

    timers.forEach(function (timer) {
        initializeClock(timer, deadline);
    });
})();