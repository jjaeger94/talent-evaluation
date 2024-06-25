<form id="resumeForm" method="post" enctype="multipart/form-data" class="mb-4">
<input type="hidden" name="talent_id" value="<?php echo $talent->ID; ?>">
        <div class="form-group">
            <?php if(empty($resumes)): ?>
                <label for="resume">Lebenslauf hochladen:</label>
            <?php else: ?>
                <label for="resume">Lebenslauf ersetzen:</label>
            <?php endif ?>
            <input type="file" id="resume" name="resume" class="form-control-file">
        </div>
        <div class="wrap mt-3">
            <span id="resume-result"></span>
        </div>
    </form>
<script>
jQuery(document).ready(function($) {
    $('#resumeForm').change(function(e) {
        e.preventDefault(); // Verhindert das Standardformulareinreichungsverhalten
        if($('#talentDetailForm')[0]){
            $('#talentDetailForm').trigger('submit');
        }
        
        // Formulardaten sammeln
        var formData = new FormData(this);
        formData.append('action', 'upload_resume'); // Aktion hinzufügen
        
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
                    $('#resume-result').text('Fehler: ' + response.data);
                }
            },
            error: function(xhr, status, error) {
                // Fehlerbehandlung
                console.error(error);
                $('#resume-result').text('Ein Fehler ist aufgetreten.');
            }
        });
    });
});
</script>
