<form method="get" class="mb-3">
    <div class="form-group row">
        <div class="col-md-5">
            <input type="text" class="form-control" id="ref" name="ref" placeholder="Referenz eingeben" value="<?php echo isset($_GET['ref']) ? esc_attr($_GET['ref']) : ''; ?>">
        </div>
        <div class="col-md-5">
            <select class="form-select" id="state" name="state">
                <option value="" <?php echo ($selected_state == '') ? 'selected' : ''; ?>>Alle Status</option>
                <option value="new" <?php echo ($selected_state == 'new') ? 'selected' : ''; ?>>Neu</option>
                <option value="registered" <?php echo ($selected_state == 'registered') ? 'selected' : ''; ?>>Registriert</option>
                <option value="waiting" <?php echo ($selected_state == 'waiting') ? 'selected' : ''; ?>>Warten</option>
                <option value="in_progress" <?php echo ($selected_state == 'in_progress') ? 'selected' : ''; ?>>In bearbeitung</option>
            </select>
        </div>
        <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-primary">Filtern</button>
        </div>
    </div>
</form>
