<div class="form-group mb-3">
    <label for="school"><strong>Schulabschluss:</strong></label>
    <select class="form-select" id="school" name="school" required>
    <option value="0" <?php echo ($talent->school == 0) ? 'selected' : ''; ?> >Kein Abschluss</option>
    <option value="1" <?php echo ($talent->school == 1) ? 'selected' : ''; ?> >Hauptschulabschluss</option>
    <option value="2" <?php echo ($talent->school == 2) ? 'selected' : ''; ?> >Realschulabschluss und vergleichbare Schulabschlüsse</option>
    <option value="3" <?php echo ($talent->school == 3) ? 'selected' : ''; ?> >Fachhochschulreife</option>
    <option value="4" <?php echo ($talent->school == 4) ? 'selected' : ''; ?> >Abitur</option>
    </select>
</div>