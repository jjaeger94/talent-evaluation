<?php if ( $candidate ) : ?>
    <div class="candidate-details">
        <h2>Bewerbungsdetails</h2>
        <ul>
            <li><strong>ID:</strong> <?php echo esc_html( $candidate->ID ); ?></li>
            <li><strong>Job ID:</strong> <?php echo esc_html( $candidate->job_id ); ?></li>
            <li><strong>Vorname:</strong> <?php echo esc_html( $candidate->prename ); ?></li>
            <li><strong>Nachname:</strong> <?php echo esc_html( $candidate->surname ); ?></li>
            <li><strong>E-Mail:</strong> <?php echo esc_html( $candidate->email ); ?></li>
            <!-- Weitere Details hier -->
        </ul>
    </div>
<?php else : ?>
    <div class="alert alert-warning" role="alert">Es wurden keine Bewerbungsdetails gefunden.</div>
<?php endif; ?>
