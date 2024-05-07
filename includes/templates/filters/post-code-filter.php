<form method="get" action="">
    <div class="row align-items-center">
        <div class="col-md-4">
            <div class="mb-3">
                <label for="postal_code" class="form-label">Postleitzahl</label>
                <input type="text" class="form-control" id="postal_code" name="postal_code" value="<?php echo esc_attr( $postal_code ); ?>" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label for="radius" class="form-label">Radius (in km)</label>
                <input type="number" class="form-control" id="radius" name="radius" value="<?php echo esc_attr( $radius ); ?>" min="5" max="100" required>
            </div>
        </div>
        <div class="col-md-2 align-self-end">
            <div class="mb-3">
                <button type="submit" class="btn btn-primary" name="submit">Filtern</button>
            </div>
        </div>
    </div>
</form>
