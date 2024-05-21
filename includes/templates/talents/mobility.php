<p class="card-title"><strong>Mobilität:</strong></p>
<div class="form-check mb-1">
    <input class="form-check-input" type="checkbox" id="license" name="license" <?php echo $talent->license ? 'checked' : ''; ?>>
    <label class="form-check-label" for="dataProcessingCheckbox">Ich habe einen Führerhschein (Klasse B)</label>
</div>
<div class="form-group mb-3">
    <label for="field">Maximale Distanz zur neuen Stelle:</label><?php echo info_button('personal_data_mobility'); ?>
    <select class="form-select" id="mobility" name="mobility" required>
    <option value="20" <?php echo ($talent->mobility == 20) ? 'selected' : ''; ?> >bis 20 km</option>
    <option value="50" <?php echo ($talent->mobility == 50) ? 'selected' : ''; ?> >bis 50 km</option>
    <option value="100" <?php echo ($talent->mobility == 100) ? 'selected' : ''; ?> >bis 100 km</option>
    <option value="0" <?php echo ($talent->mobility == 0) ? 'selected' : ''; ?> >über 100 km</option>
    </select>
</div>