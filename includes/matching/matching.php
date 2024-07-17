<div class="swiper">
    <div class="no-more-cards" style="display: none;">
        <p>Momentan gibt es keine weiteren Angebote für dich.</p>
        <p>Wir benachrichtigen dich, sobald neue Stellen verfügbar sind.</p>
        <p>Bitte beachte, dass es nach dem Erstgespräch etwas dauern kann, bis die ersten Stellen erscheinen.</p>
        <p>Du hattest kein Erstgespräch?</p>
        <button class="btn btn-primary" id="consultation">Erstgespräch anfordern</button>
        <div class="wrap">
            <span id="consultationResult"></span>
        </div>
    </div>
    <div class="swiper--status">
        <i class="fa fa-xmark"></i>
        <i class="fa fa-heart"></i>
    </div>
    <div class="swiper--cards">
        <?php foreach ($matching as $index => $match) : ?>
        <?php $job=get_job_by_id($match->job_id); ?>
        <div class="swiper--card" data-matching-id="<?php echo $match->ID; ?>">
            <p><?php echo nl2br($job->job_info); ?></p>
            <p><strong><?php echo esc_html($job->job_title); ?></strong></p>
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
                <h5 class="modal-title" id="infoModalLabel">So funktioniert das Matching:</h5>
                <button class="btn-close" id="info-btn-close" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Anonyme Stellenanzeigen:</strong> Alle Stellenangebote, die du bei uns findest, sind anonymisiert. Das bedeutet, dass wir die Stellen wertneutral wiedergeben und auf die wichtigen Aspekte der Position fokussieren. Auf diese Weise möchten wir sicherstellen, dass du dich vollständig auf die Inhalte und Anforderungen der Stellen konzentrieren kannst, ohne von Namen oder Marken beeinflusst zu werden.</p>

                <p><strong>Swipe & Match:</strong> Du wirst verschiedene Stellenangebote sehen, die auf deine Profilangaben und Präferenzen abgestimmt sind. Mit einem einfachen Swipe nach rechts kannst du dein Interesse an einer Stelle bekunden, während ein Swipe nach links bedeutet, dass diese Position nicht deinen Vorstellungen entspricht.</p>

                <p><strong>Bewertungen und Kommentare:</strong> Nach Durchsicht mehrerer Angebote kannst du deine Erfahrungen und Eindrücke bewerten. Dies hilft uns, den Matching-Prozess kontinuierlich zu verbessern und dir noch passendere Angebote zu präsentieren.</p>
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
<!-- Modal für Consultation -->
<div class="modal fade" id="consultationModal" tabindex="-1" role="dialog" aria-labelledby="consultationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="consultationModalLabel">Erstgespräch buchen</h5>
                <button class="btn-close" id="consultation-btn-close" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="calendly-inline-widget" data-url="https://calendly.com/jesse-grundke/kennenlernen-convii?name=<?php echo $talent->prename; ?>%20<?php echo $talent->surname; ?>&email=<?php echo $talent->email; ?>&text_color=454555&primary_color=a7a8cd" style="min-width:320px;height:700px;"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://assets.calendly.com/assets/external/widget.js" async></script>
<script>
jQuery(document).ready(function($) {
    $('#rating').on('input', function() {
        $('#ratingValue').text($(this).val());
    });

    $('#evaluation-btn-close').click(function() {
        $('#evaluationModal').modal('hide');
    });
    $('#consultation-btn-close').click(function() {
        $('#consultationModal').modal('hide');
    });
    $('#info-btn-close').click(function() {
        $('#infoModal').modal('hide');
    });

    $('#consultation').click(function() {
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'request_consultation',
                talent_id: <?php echo $talent->ID; ?>
            },
            success: function(response) {
                if (response.success) {
                    console.log('Success: ' + response.data);
                    $('#consultation').hide();
                    $('#consultationModal').modal('show');
                } else {
                    console.log('Error: ' + response.data);
                    $('#consultationResult').text('Ein fehler ist aufgetreten');
                }
            },
            error: function() {
                console.log('AJAX request failed.');
                $('#consultationResult').text('Ein fehler ist aufgetreten');
            }
        });
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
    var noMoreCardsText = $('.no-more-cards')

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
            noMoreCardsText.show();
            if(removedCards.length > 0){
                $('#evaluationModal').modal('show');
            }
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
                var matchingId = $(el).data('matching-id');
                var state = event.deltaX > 0 ? 2 : 1;
                sendSwipeAction(matchingId, state);
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

    $('#infoModal').modal('show');
});
</script>
<style type="text/css">
html, body {
  overflow: hidden;
}
</style>
