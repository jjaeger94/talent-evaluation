<div class="chat-bar fixed-top" style="z-index: 1000;">
    <div class="container">
        <div class="row align-items-center">
            <!-- Menü -->
            <div class="col-2">
               
            </div>
            <!-- Name von Dieter -->
            <div class="col-8 text-center">
                <h4>Stellen</h4>
            </div>
            <!-- Icon mit Fragezeichen -->
            <div class="col-2 text-right">
                <div class="info-button" id="help-matching-open">
                    <i class="fa-regular fa-circle-question"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include TE_DIR.'matching/matching.php'; ?>

<!-- Modal für Tipps -->
<div class="modal fade" id="helpModal" tabindex="-1" role="dialog" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="helpModalLabel">Jobmatching</h5>
            </div>
            <div class="modal-body">
                <p>Wähle im Jobmatching aus welche Stellen für dich interessant sind.</p>
                <p>Wische die Stellen die dir gefallen nach Links oder drücke unten auf das Herz. Die Stellen die dir nicht zusagen kannst du mit einem klick auf das X oder einem Swipe nach links entfernen.</p>
                <p>Mit einem Tippen auf den Text werden dir mehr Informationen zur Stelle angezeigt.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="help-btn-close">Schließen</button>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('#help-matching-open').click(function() {
        $('#helpModal').modal('show');
    });
    $('#help-btn-close').click(function() {
        $('#helpModal').modal('hide');
    });
});
</script>