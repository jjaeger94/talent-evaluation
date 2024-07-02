<form id="documentForm" method="post" enctype="multipart/form-data" class="mb-4">
<input type="hidden" name="talent_id" value="<?php echo $talent->ID; ?>">
<input type="hidden" name="type" value="2">
        <div class="form-group">
            <?php if(empty($documents)): ?>
                <label for="document">Dokument hochladen:</label>
            <?php else: ?>
                <label for="document">Dokument hinzufügen:</label>
            <?php endif ?>
            <input type="file" id="document" name="document" class="form-control-file">
        </div>
        <div class="wrap mt-3">
            <span id="document-result"></span>
        </div>
    </form>
<script>
jQuery(document).ready(function($) {
    $('#documentForm').change(function(e) {
        e.preventDefault(); // Verhindert das Standardformulareinreichungsverhalten
        if($('#talentDetailForm')[0]){
            $('#talentDetailForm').trigger('submit');
        }
        
        // Formulardaten sammeln
        var formData = new FormData(this);
        formData.append('action', 'upload_document'); // Aktion hinzufügen
        
        // AJAX-Anfrage senden
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Erfolgreiche Verarbeitung
                console.log(response);
                if(response.success){
                    location.reload();
                } else {
                    $('#document-result').text('Fehler: ' + response.data);
                }
            },
            error: function(xhr, status, error) {
                // Fehlerbehandlung
                console.error(error);
                $('#document-result').text('Ein Fehler ist aufgetreten.');
            }
        });
    });
});
</script>
