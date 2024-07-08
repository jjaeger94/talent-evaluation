<form method="get" class="mb-3">
    <div class="form-group row">
        <div class="col-md-5">
            <label for="value" class="form-label">Bewerbersstatus:</label>
            <select class="form-select" id="value" name="value">
                <option value="" <?php echo ($selected_value < 0) ? 'selected' : ''; ?>>Alle Status</option>
                <?php for ($i = 0; $i <= 6; $i++) : ?>
                    <option value="<?php echo $i; ?>" <?php echo ($selected_value == $i) ? 'selected' : ''; ?>>
                        <?php echo get_matching_state($i); ?>
                    </option>
                <?php endfor; ?>
                <option value="99" <?php echo ($selected_value == 99) ? 'selected' : ''; ?>>Abgelehnt</option>
            </select>
        </div>
        <div class="col-md-5">
            <label for="state" class="form-label">Bearbeitungsstatus:</label>
            <input type="text" class="form-control" id="state" name="state" value="<?php echo $state; ?>">
        </div>
        <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-primary">Filtern</button>
        </div>
    </div>
</form>
