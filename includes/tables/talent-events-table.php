<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Typ</th>
                <th>Beschreibung</th>
                <th>User</th>
                <th>Datum</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $event) : ?>
                <tr>
                    <td><?php echo get_event_state($event->event_type); ?></td>
                    <td><?php echo esc_html($event->event_description); ?></td>
                    <td><?php echo esc_html(get_display_name($event->user_id)); ?></td>
                    <td><?php echo esc_html($event->added); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
