<form method="get">
    <div class="form-group row">
        <div class="col-md-5">
            <label for="state" class="form-label">Jobstatus:</label>
            <select class="form-select" id="state" name="state">
                <option value="-1" <?php echo ($selected_state == -1) ? 'selected' : ''; ?>>Alle Status</option>
                <option value="1" <?php echo ($selected_state == 1) ? 'selected' : ''; ?>>Aktiv</option>
                <option value="0" <?php echo ($selected_state == 0) ? 'selected' : ''; ?>>Inaktiv</option>
            </select>
        </div>
        <div class="col-md-5">
            <label for="notes" class="form-label">Bearbeitungsstatus:</label>
            <input type="text" class="form-control" id="notes" name="notes" value="<?php echo $notes; ?>">
        </div>
        <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-primary">Filtern</button>
        </div>
    </div>
</form>