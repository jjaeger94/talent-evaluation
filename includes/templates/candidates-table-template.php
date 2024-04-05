<?php if ( ! empty( $candidates ) ) : ?>
    <table class="table">
        <thead>
            <tr>
                <th>Kandidat</th>
                <th>Stelle</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $candidates as $candidate ) : ?>
                <tr>
                    <td><?php echo esc_html( $candidate->prename . ' ' . $candidate->surname ); ?></td>
                    <td><?php echo esc_html( $candidate->job_title ); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p>Keine Kandidaten gefunden.</p>
<?php endif; ?>
