<div class="swiper">
    <div class="swiper--status">
        <i class="fa fa-xmark"></i>
        <i class="fa fa-heart"></i>
    </div>
    <div class="swiper--cards">
        <?php foreach ($matching as $index => $match) : ?>
        <?php $job=get_job_by_id($match->job_id); ?>
        <div class="swiper--card" data-matching-id="<?php echo $match->ID; ?>">
            <p><strong><?php echo esc_html($job->job_title); ?></strong></p>
            <p><?php echo nl2br($job->job_info); ?></p>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="swiper--buttons">
        <button id="nope"><i class="fa fa-xmark"></i></button>
        <button id="love"><i class="fa fa-heart"></i></button>
    </div>
</div>
<div class="no-more-cards" style="display: none;">
    <p>Momentan gibt es keine weiteren Angebote für dich, schaue später noch einmal hinein.</p>
</div>

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
    var noMoreCardsText = $('.no-more-cards')

    function initCards() {
        var newCards = $('.swiper--card:not(.removed)');

        newCards.each(function(index) {
            $(this).css('z-index', allCards.length - index);
            $(this).css('transform', 'scale(' + (20 - index) / 20 + ') translateY(-' + 30 * index + 'px)');
            $(this).css('opacity', (10 - index) / 10);
        });

        swiperContainer.addClass('loaded');

        // Überprüfe, ob keine Karten mehr vorhanden sind und zeige den Hinweistext
        if (newCards.length === 0) {
            noMoreCardsText.show();
        } else {
            noMoreCardsText.hide();
        }
    }

    initCards();

    function sendSwipeAction(matchingId, state) {
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'save_matching',
                matching_id: matchingId,
                matching: state
            },
            success: function(response) {
                if (response.success) {
                    console.log('Success: ' + response.data);
                } else {
                    console.log('Error: ' + response.data);
                }
            },
            error: function() {
                console.log('AJAX request failed.');
            }
        });
    }

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
                var matchingId = $(el).data('matching-id');
                var state = event.deltaX > 0 ? 2 : 1;
                sendSwipeAction(matchingId, state);
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
            var matchingId = card.data('matching-id');
            var state = love ? 2 : 1;
            if (love) {

                card.css('transform', 'translate(' + moveOutWidth + 'px, -100px) rotate(-30deg)');
            } else {
                card.css('transform', 'translate(-' + moveOutWidth + 'px, -100px) rotate(30deg)');
            }

            sendSwipeAction(matchingId, state);
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
