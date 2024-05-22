<a href="#" id="start-test-btn" class="btn btn-primary">Test starten</a>

<!-- Popup-Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Bestätigung erforderlich</h5>
            </div>
            <div class="modal-body">
                Sind Sie sicher, dass Sie den Test starten möchten?
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="dismiss-start-btn">Abbrechen</button>
                <a href="<?php echo $link; ?>" id="confirm-start-btn" class="btn btn-primary">Ja, Test starten</a>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript, um das Popup-Modal zu öffnen
    jQuery(document).ready(function($) {
        // Klicken Sie auf den Start-Test-Button
        $('#start-test-btn').on('click', function () {
            // Öffnen Sie das Popup-Modal
            $('#confirmationModal').modal('show');
        });

        // Klicken Sie auf den Bestätigungsbutton im Popup-Modal
        $('#confirm-start-btn').on('click', function () {
            // Führen Sie die Aktion aus, z. B. öffnen Sie den Link
            window.location.href = this.href;
        });

        // Klicken Sie auf den Bestätigungsbutton im Popup-Modal
        $('#dismiss-start-btn').on('click', function () {
            // Führen Sie die Aktion aus, z. B. öffnen Sie den Link
            $('#confirmationModal').modal('hide');
        });
    });
</script>

