<table class="table table-bordered mt-4">
    <thead>
        <tr>
            <th>Datei</th>
            <th>Hinzugefügt</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($docs as $doc) : ?>
            <tr>
                <td><?php echo esc_html($doc['file']); ?></td>
                <td><?php echo esc_html($doc['added']); ?></td>
                <?php if (has_service_permission()) : ?>
                    <td><button class="btn btn-secondary download-document" data-document-id="<?php echo esc_attr($doc['ID']); ?>">Herunterladen</button></td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
jQuery(document).ready(function($) {
    // Entfernen des bestehenden Klick-Listeners, falls vorhanden
    $('.download-document').off('click');
    // Hinzufügen des neuen Klick-Listeners
    $('.download-document').on('click', function() {
        var document_id = $(this).data('document-id');

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'download_document',
                document_id: document_id
            },
            success: function(response) {
                if (response.success) {
                    var file_url = response.data.file_url;
                    var link = document.createElement('a');
                    link.href = file_url;
                    link.download = file_url.substring(file_url.lastIndexOf('/') + 1);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                } else {
                    alert('Fehler: ' + response.data);
                }
            },
            error: function(xhr, status, error) {
                alert('Ein Fehler ist aufgetreten: ' + error);
            }
        });
    });
});
</script>
