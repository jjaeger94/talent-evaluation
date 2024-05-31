<?php if (!empty($matching)) : ?>
<div class="swiper">
    <div class="swiper--status">
        <i class="fa fa-xmark"></i>
        <i class="fa fa-heart"></i>
    </div>
    <div class="swiper--cards">
        <?php foreach ($matching as $index => $match) : ?>
        <?php $job=get_job_by_id($match->job_id); ?>
        <?php for ($i = 0; $i < 10; $i++) : ?>
        <div class="swiper--card">
            <p><strong><?php echo esc_html($job->job_title); ?></strong></p>
            <p><?php echo nl2br($job->job_info); ?></p>
        </div>
        <?php endfor; ?>
        <?php endforeach; ?>
    </div>
    <div class="swiper--buttons">
        <button id="nope"><i class="fa fa-xmark"></i></button>
        <button id="love"><i class="fa fa-heart"></i></button>
    </div>
</div>
<?php else : ?>    
<div class="alert alert-warning">Momentan gibt es keine Angebote für dich, schaue später noch einmal hinein.</div>
<?php endif; ?>

<!-- Modal für ganzen Text -->
<div class="modal fade" id="textModal" tabindex="-1" role="dialog" aria-labelledby="textModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="textModalLabel">Stellenbeschreibung</h5>
                <button class="btn-close" id="text-btn-close" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-text"></div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('#text-btn-close').click(function() {
        $('#textModal').modal('hide');
    });
    var swiperContainer = $('.swiper');
    var allCards = $('.swiper--card');
    var nope = $('#nope');
    var love = $('#love');

    function initCards() {
        var newCards = $('.swiper--card:not(.removed)');

        newCards.each(function(index) {
            $(this).css('z-index', allCards.length - index);
            $(this).css('transform', 'scale(' + (20 - index) / 20 + ') translateY(-' + 30 * index + 'px)');
            $(this).css('opacity', (10 - index) / 10);
        });

        swiperContainer.addClass('loaded');
    }

    initCards();

    allCards.each(function() {
        var el = this;
        var hammertime = new Hammer(el);

        hammertime.on('pan', function(event) {
            $(el).addClass('moving');
            if (event.deltaX === 0) return;
            if (event.center.x === 0 && event.center.y === 0) return;

            swiperContainer.toggleClass('swiper_love', event.deltaX > 0);
            swiperContainer.toggleClass('swiper_nope', event.deltaX < 0);

            var xMulti = event.deltaX * 0.03;
            var yMulti = event.deltaY / 80;
            var rotate = xMulti * yMulti;

            $(el).css('transform', 'translate(' + event.deltaX + 'px, ' + event.deltaY + 'px) rotate(' + rotate + 'deg)');
        });

        hammertime.on('panend', function(event) {
            $(el).removeClass('moving');
            swiperContainer.removeClass('swiper_love swiper_nope');

            var moveOutWidth = document.body.clientWidth;
            var keep = Math.abs(event.deltaX) < 80 || Math.abs(event.velocityX) < 0.5;

            $(el).toggleClass('removed', !keep);

            if (keep) {
                $(el).css('transform', '');
            } else {
                var endX = Math.max(Math.abs(event.velocityX) * moveOutWidth, moveOutWidth);
                var toX = event.deltaX > 0 ? endX : -endX;
                var endY = Math.abs(event.velocityY) * moveOutWidth;
                var toY = event.deltaY > 0 ? endY : -endY;
                var xMulti = event.deltaX * 0.03;
                var yMulti = event.deltaY / 80;
                var rotate = xMulti * yMulti;

                $(el).css('transform', 'translate(' + toX + 'px, ' + (toY + event.deltaY) + 'px) rotate(' + rotate + 'deg)');
                initCards();
            }
        });

        hammertime.on('tap', function(event) {
            var jobInfo = $(el).find('p').last().html();
            $('#modal-text').html(jobInfo);
            $('#textModal').modal('show');
            history.pushState({modalOpen: true}, null, null);
        });
    });

    function createButtonListener(love) {
        return function(event) {
            var cards = $('.swiper--card:not(.removed)');
            var moveOutWidth = document.body.clientWidth * 1.5;

            if (!cards.length) return false;

            var card = cards.first();

            card.addClass('removed');

            if (love) {
                card.css('transform', 'translate(' + moveOutWidth + 'px, -100px) rotate(-30deg)');
            } else {
                card.css('transform', 'translate(-' + moveOutWidth + 'px, -100px) rotate(30deg)');
            }

            initCards();

            event.preventDefault();
        };
    }

    var nopeListener = createButtonListener(false);
    var loveListener = createButtonListener(true);

    nope.on('click', nopeListener);
    love.on('click', loveListener);
});
</script>
<style type="text/css">
html, body {
  overflow: hidden;
}
</style>
