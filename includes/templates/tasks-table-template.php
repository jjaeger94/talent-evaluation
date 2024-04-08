<?php if ( ! empty( $tasks ) ) : ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>E-Mail</th>
                    <th>Hinzugefügt</th>
                    <th>Editiert</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $tasks as $task ) : ?>
                    <tr>
                        <td><?php echo esc_html( $task->state ); ?></td>
                        <td><?php echo esc_html( $task->email ); ?></td>
                        <td><?php echo esc_html( $task->added ); ?></td>
                        <td><?php echo esc_html( $task->edited ); ?></td>
                        <td><a href="<?php echo esc_url( home_url( '/bewerbung-details?id=' . $task->ID ) ); ?>">Details anzeigen</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else : ?>
    <p>Keine Aufgaben gefunden.</p>
<?php endif; ?>
