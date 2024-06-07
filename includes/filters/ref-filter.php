<form method="get">
    <div class="form-group row">
        <div class="col-md-6">
            <input type="text" class="form-control" id="ref" name="ref" placeholder="Referenz eingeben" value="<?php echo isset($_GET['ref']) ? esc_attr($_GET['ref']) : ''; ?>">
        </div>
        <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-primary">Filtern</button>
        </div>
    </div>
</form>
