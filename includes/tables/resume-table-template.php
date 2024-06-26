<table class="table table-bordered mt-4">
    <thead>
        <tr>
            <th>Datei</th>
            <th>Hinzugefügt</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($resumes as $resume) : ?>
            <tr>
                <td><?php echo esc_html($resume['file']); ?></td>
                <td><?php echo esc_html($resume['added']); ?></td>
                <td><button class="btn btn-secondary download-resume" data-resume-id="<?php echo esc_attr($resume['ID']); ?>">Herunterladen</button></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
jQuery(document).ready(function($) {
    $('.download-resume').on('click', function() {
        var resume_id = $(this).data('resume-id');

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'download_resume',
                resume_id: resume_id
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
