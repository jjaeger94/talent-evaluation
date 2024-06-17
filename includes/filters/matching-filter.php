<form method="get" class="mb-3">
    <div class="form-group row">
        <div class="col-md-6">
            <select class="form-select" id="state" name="state">
                <option value="" <?php echo ($selected_state < 0) ? 'selected' : ''; ?>>Alle Status</option>
                <option value="0" <?php echo ($selected_state == 0) ? 'selected' : ''; ?>><?php echo get_matching_state(0); ?></option>
                <option value="1" <?php echo ($selected_state == 1) ? 'selected' : ''; ?>><?php echo get_matching_state(1); ?></option>
                <option value="2" <?php echo ($selected_state == 2) ? 'selected' : ''; ?>><?php echo get_matching_state(2); ?></option>
            </select>
        </div>
        <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-primary">Filtern</button>
        </div>
    </div>
</form>
