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
                <td><a href="<?php echo esc_url(add_query_arg('download_file', $resume['ID'], site_url('/protected/')))?>">Lebenslauf herunterladen</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>