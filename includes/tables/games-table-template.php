<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Key</th>
                <th>Assistant ID</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($games as $game) : ?>
                <tr>
                    <td><a href="<?php echo esc_url(home_url('/game-details/?id=' . $game->ID)); ?>"><?php echo $game->gamekey; ?></a></td>
                    <td><?php echo $game->assistant_id; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>