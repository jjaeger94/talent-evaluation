<div id="talentFormModal" class="modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Super, du hast es geschafft!</h5>
                <!-- <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
            </div>
            <div class="modal-body">
                <div class="alert alert-info w-100">Hast du Interesse dein Talent zu nutzen? Hinterlasse hier deine Daten um von spannenden Unternehmen aus deiner Gegend kontaktiert zu werden!</div>
                <form id="talentForm" method="post">
                    <input type="hidden" name="action" value="submit_talent_form">
                    <div class="mb-3">
                        <label for="prename" class="form-label">Vorname:</label>
                        <input type="text" class="form-control" id="prename" name="prename" required>
                    </div>
                    <div class="mb-3">
                        <label for="surname" class="form-label">Nachname:</label>
                        <input type="text" class="form-control" id="surname" name="surname" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-Mail:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="mobile" class="form-label">Telefonnummer:</label>
                        <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="+4917123456789" pattern="^[0-9\+\-]+$"required>
                    </div>
                    <div class="mb-3">
                        <label for="post_code" class="form-label">Postleitzahl:</label>
                        <input type="text" class="form-control" id="post_code" name="post_code" pattern="\d{5}"required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="acceptPrivacy" name="acceptPrivacy" required>
                        <label class="form-check-label" for="acceptPrivacy">Ich akzeptiere die <a href="/datenschutzerklaerung" target="_blank" rel="noopener noreferrer">Datenschutzerklärung</a></label>
                    </div>
                    <button type="submit" class="btn btn-primary">Absenden</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function($) {
    $('#talentForm').submit(function(e) {
        e.preventDefault(); // Verhindert das Standardformulareinreichungsverhalten

        // Formulardaten sammeln
        var formData = $(this).serialize();

        // Hier wird der Wert des Parameters 'ad' aus dem URL-Parameter geholt
        var urlParams = new URLSearchParams(window.location.search);
        

        var data = formData + '&action=save_talent';
        if(urlParams && urlParams.has('ad')){
            var adValue = urlParams.get('ad');
            data = data + '&ad=' + adValue;
        }

        // AJAX-Anfrage senden
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: data,
            success: function(response) {
                // Erfolgreiche Verarbeitung
                console.log(response);
                if(response.success){
                    $('#talentFormModal').modal('hide');
                }
                // Hier können Sie je nach Bedarf weitere Aktionen ausführen
            },
            error: function(xhr, status, error) {
                // Fehlerbehandlung
                console.error(error);
            }
        });
    });
});
</script>
