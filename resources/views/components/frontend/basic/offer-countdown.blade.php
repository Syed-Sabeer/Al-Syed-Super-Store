<div class="remaining-time">
    <div class="remaining-time__content">
        <p class="box"><span class="box__days box-style">00</span> <span class="box__text">@lang('Days')</span></p>
        <p class="box"><span class="remaining-time__hrs box-style">00</span> <span class="box__text">@lang('Hours')</span></p>
        <p class="box"><span class="remaining-time__min box-style">00</span> <span class="box__text">@lang('Min')</span></p>
        <p class="box"><span class="remaining-time__sec box-style">00</span> <span class="box__text">@lang('Sec')</span></p>
    </div>
</div>

@pushOnce('script')
    <script>
        "use strict";

        function setCountdownProgress(element, gradientAngle) {
            var gradient = "conic-gradient(hsl(var(--base)) " + gradientAngle + "deg, hsl(var(--black) / .05) 0deg)";
            element.style.background = gradient;
        }

        function initializeCountdown(offerElement) {

            var startAt = parseInt(offerElement.getAttribute('data-starts-at'));
            var endsAt = parseInt(offerElement.getAttribute('data-ends-at'));
            var totalDuration = endsAt - startAt;

            var countdownInterval = setInterval(function() {
                var now = Date.now();
                var distance = endsAt - now;

                if (now < startAt) {
                    offerElement.querySelector(".remaining-time__content").innerHTML = "<p>@lang('Offer hasn\'t started yet')</p>";
                    return;
                }

                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                offerElement.querySelector(".box__days").textContent = days;
                offerElement.querySelector(".remaining-time__hrs").textContent = hours;
                offerElement.querySelector(".remaining-time__min").textContent = minutes;
                offerElement.querySelector(".remaining-time__sec").textContent = seconds;

                var elapsed = now - startAt;
                var progress = Math.min(elapsed / totalDuration, 1);

                if($(offerElement).find('.offer-countdown').length){
                    setCountdownProgress(offerElement.querySelector(".box__days"), 360 * (1 - progress));
                    setCountdownProgress(offerElement.querySelector(".remaining-time__hrs"), (360 / 24) * hours);
                    setCountdownProgress(offerElement.querySelector(".remaining-time__min"), (360 / 60) * minutes);
                    setCountdownProgress(offerElement.querySelector(".remaining-time__sec"), (360 / 60) * seconds);
                }

                if (distance < 0) {
                    clearInterval(countdownInterval);
                    offerElement.querySelector(".remaining-time__content").innerHTML = "<p class='expired'>@lang('Expired')</p>";
                }
            }, 1000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            var offers = document.querySelectorAll('.flash-sell-section');
            offers.forEach(function(offer) {
                if (offer.hasAttribute('data-starts-at') && offer.hasAttribute('data-ends-at')) {
                    initializeCountdown(offer);
                }
            });
        });
    </script>
@endPushOnce

