<div class="swiper">
    <div class="swiper--status">
        <i class="fa fa-xmark"></i>
        <i class="fa fa-heart"></i>
    </div>
    <div class="swiper--cards">
        <?php foreach ($difference_ids as $job_id) : ?>
        <div class="swiper--card" data-job-id="<?php echo $job_id; ?>">
            <p><?php echo nl2br($jobs_by_id[$job_id]->job_info); ?></p>
            <p><strong><?php echo esc_html($jobs_by_id[$job_id]->job_title); ?></strong></p>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="swiper--buttons">
        <button id="nope"><i class="fa fa-xmark"></i></button>
        <button id="love"><i class="fa fa-heart"></i></button>
    </div>
</div>

<!-- Modal für info Text -->
<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLabel">So funktionierts:</h5>
                <button class="btn-close" id="info-btn-close" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Swipe & Match:</strong> Du wirst verschiedene Beispielstellen sehen. Mit einem einfachen Swipe nach rechts kannst du dein Interesse an einer Stelle bekunden, während ein Swipe nach links bedeutet, dass diese Position nicht deinen Vorstellungen entspricht. Durch deine Auswahl können wir echte Stellenangebote besser auf deine Wünsche abstimmen.</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal für ganzen Text -->
<div class="modal fade" id="textModal" tabindex="-1" role="dialog" aria-labelledby="textModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="textModalLabel">Stellenbeschreibung</h5>
                <button class="btn-close" id="text-btn-close" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="modal-text"></p>
                <p id="modal-title"></p>
            </div>
        </div>
    </div>
</div>

<!-- Modal für Bewertung -->
<div class="modal fade" id="evaluationModal" tabindex="-1" role="dialog" aria-labelledby="evaluationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="evaluationModalLabel">Bewertung</h5>
                <button class="btn-close" id="evaluation-btn-close" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="evaluationForm">
                    <p>Bitte teile uns mit, wie dir die Stellen gefallen haben. Was für weitere Stellenangebote würdest du dir wünschen?</p>
                    <div class="mb-3">
                        <label for="rating" class="form-label">Bewertung: <span id="ratingValue">5</span></label>
                        <input type="range" class="form-range" id="rating" name="rating" min="1" max="10" required>
                        <div class="d-flex justify-content-between">
                            <span>1</span>
                            <span>10</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Kommentar</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Absenden</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function($) {
    $('#rating').on('input', function() {
        $('#ratingValue').text($(this).val());
    });

    $('#evaluation-btn-close').click(function() {
        $('#evaluationModal').modal('hide');
        location.reload();
    });
    $('#info-btn-close').click(function() {
        $('#infoModal').modal('hide');
    });

    $('#evaluationForm').submit(function(event) {
        event.preventDefault();
        var rating = $('#rating').val();
        var comment = $('#comment').val();

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'submit_evaluation',
                rating: rating,
                comment: comment,
                talent_id: <?php echo $talent->ID; ?>
            },
            success: function(response) {
                if (response.success) {
                    console.log('Evaluation submitted: ' + response.data);
                } else {
                    console.log('Error: ' + response.data);
                }
                $('#evaluationModal').modal('hide');
                location.reload();
            },
            error: function() {
                console.log('AJAX request failed.');
                $('#evaluationModal').modal('hide');
            }
        });
    });
    $('#text-btn-close').click(function() {
        $('#textModal').modal('hide');
    });
    var swiperContainer = $('.swiper');
    var allCards = $('.swiper--card');
    var nope = $('#nope');
    var love = $('#love');

    function initCards() {
        var newCards = $('.swiper--card:not(.removed)');
        var removedCards = $('.swiper--card.removed');

        newCards.each(function(index) {
            $(this).css('z-index', allCards.length - index);
            $(this).css('transform', 'scale(' + (20 - index) / 20 + ') translateY(-' + 30 * index + 'px)');
            $(this).css('opacity', (10 - index) / 10);
        });

        swiperContainer.addClass('loaded');

        // Überprüfe, ob keine Karten mehr vorhanden sind und zeige den Hinweistext
        if (newCards.length === 0) {
            if(removedCards.length > 0){
                $('#evaluationModal').modal('show');
            }
        }
    }

    initCards();

    function sendSwipeAction(jobId, state) {
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'save_preference',
                job_id: jobId,
                talent_id: <?php echo $talent->ID; ?>,
                preference: state
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
            var keep = Math.abs(event.deltaX) < 80 || ((window.screen.height > window.screen.width) && Math.abs(event.velocityX) < 0.5);
            console.log(Math.abs(event.deltaX));
            console.log(Math.abs(event.velocityX));

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
                var jobId = $(el).data('job-id');
                var state = event.deltaX > 0 ? 2 : 1;
                sendSwipeAction(jobId, state);
                initCards();
            }
        });

        hammertime.on('tap', function(event) {
            var jobInfo = $(el).find('p').first().html();
            var jobTitle = $(el).find('p').last().html();
            $('#modal-text').html(jobInfo);
            $('#modal-title').html(jobTitle);
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
            var jobId = card.data('job-id');
            var state = love ? 2 : 1;
            if (love) {

                card.css('transform', 'translate(' + moveOutWidth + 'px, -100px) rotate(-30deg)');
            } else {
                card.css('transform', 'translate(-' + moveOutWidth + 'px, -100px) rotate(30deg)');
            }

            sendSwipeAction(jobId, state);
            initCards();

            event.preventDefault();
        };
    }

    var nopeListener = createButtonListener(false);
    var loveListener = createButtonListener(true);

    nope.on('click', nopeListener);
    love.on('click', loveListener);
    $('#infoModal').modal('show');
});
</script>
<style type="text/css">
html, body {
  overflow: hidden;
}
</style>
