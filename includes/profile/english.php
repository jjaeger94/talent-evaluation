<div class="form-group mb-3">
    <label for="english"><strong>Englischkenntnisse:</strong></label>
    <select class="form-select" id="english" name="english" required>
    <option value="0" <?php echo ($talent->english == 0) ? 'selected' : ''; ?> ><?php echo get_english_level(0); ?></option>
    <option value="1" <?php echo ($talent->english == 1) ? 'selected' : ''; ?> ><?php echo get_english_level(1); ?></option>
    <option value="2" <?php echo ($talent->english == 2) ? 'selected' : ''; ?> ><?php echo get_english_level(2); ?></option>
    <option value="3" <?php echo ($talent->english == 3) ? 'selected' : ''; ?> ><?php echo get_english_level(3); ?></option>
    </select>
</div>
