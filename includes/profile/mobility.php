<p class="card-title"><strong>Mobilität:</strong></p>
<div class="form-check mb-1">
    <input class="form-check-input" type="checkbox" id="license" name="license" <?php echo $talent->license ? 'checked' : ''; ?>>
    <label class="form-check-label" for="license">Ich habe einen Führerschein (Klasse B)</label>
</div>
<div class="form-group mb-3">
    <label for="field">Distanz:</label><?php echo info_button('personal_data_mobility'); ?>
    <select class="form-select" id="mobility" name="mobility" required>
    <option value="20" <?php echo ($talent->mobility == 20) ? 'selected' : ''; ?> ><?php echo get_mobility_label(20); ?></option>
    <option value="50" <?php echo ($talent->mobility == 50) ? 'selected' : ''; ?> ><?php echo get_mobility_label(50); ?></option>
    <option value="100" <?php echo ($talent->mobility == 100) ? 'selected' : ''; ?> ><?php echo get_mobility_label(100); ?></option>
    <option value="0" <?php echo ($talent->mobility == 0) ? 'selected' : ''; ?> ><?php echo get_mobility_label(0); ?></option>
    </select>
</div>
<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" id="home_office" name="home_office" <?php echo $talent->home_office ? 'checked' : ''; ?>>
    <label class="form-check-label" for="home_office">teilweise Home Office gewünscht</label>
</div>
<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" id="part_time" name="part_time" <?php echo $talent->part_time ? 'checked' : ''; ?>>
    <label class="form-check-label" for="part_time">nur Teilzeit</label>
</div>