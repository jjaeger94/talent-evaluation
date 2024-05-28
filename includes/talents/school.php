<div class="form-group mb-3">
    <label for="school"><strong>Schulabschluss:</strong></label>
    <select class="form-select" id="school" name="school" required>
    <option value="0" <?php echo ($talent->school == 0) ? 'selected' : ''; ?> ><?php echo get_school_degree(0); ?></option>
    <option value="1" <?php echo ($talent->school == 1) ? 'selected' : ''; ?> ><?php echo get_school_degree(1); ?></option>
    <option value="2" <?php echo ($talent->school == 2) ? 'selected' : ''; ?> ><?php echo get_school_degree(2); ?></option>
    <option value="3" <?php echo ($talent->school == 3) ? 'selected' : ''; ?> ><?php echo get_school_degree(3); ?></option>
    <option value="4" <?php echo ($talent->school == 4) ? 'selected' : ''; ?> ><?php echo get_school_degree(4); ?></option>
    </select>
</div>