<div class="container">
    <div class="row">
        <div class="col-md-6">
            <!-- Text mit Affiliate-Link -->
            <p>Hier ist der Text mit dem Affiliate-Link zum Test:</p>
            <p><?php echo esc_html($test->title); ?></p>
            <p>Affiliate-Link: <a href="<?php echo esc_url($test->affiliate_link); ?>"><?php echo esc_url($test->affiliate_link); ?></a></p>
            <!-- Start-Button für den Test -->
            <a href="<?php echo $link; ?>" class="btn btn-primary">Test starten</a>
        </div>
        <div class="col-md-6">
            <!-- Bild zum Test -->
            <?php if (!empty($test->image_link)) : ?>
                <img src="<?php echo esc_url($test->image_link); ?>" alt="Testbild" class="img-fluid">
            <?php endif; ?>
        </div>
    </div>
</div>
