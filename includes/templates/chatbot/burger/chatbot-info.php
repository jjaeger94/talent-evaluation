<div class="alert alert-info w-100 p-3 fixed-top" style="z-index: 1000;">
    <div class="container">
        <div class="row align-items-center">
            <!-- Bild von Dieter -->
            <div class="col-2">
                <img src="https://commitiq.de/wp-content/uploads/2024/05/Dieter_ohne_rand.png" class="img-fluid rounded-circle" alt="Dieter" style="width: 50px;">
            </div>
            <!-- Name von Dieter -->
            <div class="col-8 text-center">
                <h4>Dieter</h4>
            </div>
            <!-- Icon mit Fragezeichen -->
            <div class="col-2 text-right">
                <button type="button" class="btn btn-outline-primary" id="help-btn-open">
                    <i class="fas fa-question-circle"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal für Tipps -->
<div class="modal fade" id="tipModal" tabindex="-1" role="dialog" aria-labelledby="tipModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tipModalLabel">Tipps für das Spiel mit Dieter</h5>
            </div>
            <div class="modal-body">
                <p>Diese Tipps können dir helfen:</p>
                <ul>
                    <li>Stelle viele Fragen, um Dieters Perspektive und Bedürfnisse zu verstehen.</li>
                    <li>Nutze deine Empathie, um eine unterstützende Atmosphäre zu schaffen.</li>
                </ul>
                <p>Das Spiel beginnt mit deiner ersten Nachricht. Viel Erfolg!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="help-btn-close">Schließen</button>
            </div>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function($) {
    $('#help-btn-open').click(function() {
        $('#tipModal').modal('show');
    });
    $('#help-btn-close').click(function() {
        $('#tipModal').modal('hide');
    });
});
</script>
