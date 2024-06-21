<form method="get" class="mb-3">
    <div class="form-group row">
        <div class="col-md-5">
            <label for="value" class="form-label">Bewerbersstatus:</label>
            <select class="form-select" id="value" name="value">
                <option value="" <?php echo ($selected_value < 0) ? 'selected' : ''; ?>>Alle Status</option>
                <option value="0" <?php echo ($selected_value == 0) ? 'selected' : ''; ?>><?php echo get_matching_state(0); ?></option>
                <option value="1" <?php echo ($selected_value == 1) ? 'selected' : ''; ?>><?php echo get_matching_state(1); ?></option>
                <option value="2" <?php echo ($selected_value == 2) ? 'selected' : ''; ?>><?php echo get_matching_state(2); ?></option>
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
