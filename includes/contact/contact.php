<div class="chat-bar fixed-top" style="z-index: 1000;">
    <div class="container">
        <div class="row align-items-center">
            <!-- Menü -->
            <div class="col-2">
                <?php include TE_DIR.'menu/burger.php'; ?>
            </div>
            <!-- Name von Dieter -->
            <div class="col-8 text-center">
                <h4>Kontakt</h4>
            </div>
            <!-- Icon mit Fragezeichen -->
            <div class="col-2 text-right">
            </div>
        </div>
        <?php include TE_DIR.'menu/entries.php'; ?>
    </div>
</div>
<div class="container mt-5">
<div class="no-more-cards">
    <p>Falls du noch Fragen zum Ablauf hast oder bisher noch kein Erstgespräch vereinbart hast, kannst du hier einen Termin buchen.</p>
    <button class="btn btn-primary" id="consultation">Erstgespräch anfordern</button>
    <div class="wrap">
        <span id="consultationResult"></span>
    </div>
    <br>
    <p>Oder schreibe uns einfach eine Email an <a href="mailto:kontakt@convii.de">kontakt@convii.de</a></p>
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
    $('#consultation-btn-close').click(function() {
        $('#consultationModal').modal('hide');
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
                    $('#consultationModal').modal('show');
                    $('#consultationResult').text('Gespräch erfolgreich gebucht');
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
});
</script>
