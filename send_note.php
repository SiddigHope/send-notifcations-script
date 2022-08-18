<fieldset>
    <div class="form-group">
        <label for="location">المنطفة *</label>
          <input type="text" name="location" value="<?php echo htmlspecialchars($edit ? $location['location'] : '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="المنطقة" class="form-control" id = "location">
    </div>

    <div class="form-group text-center">
        <label></label>
        <button type="submit" class="btn btn-warning" >حفظ <i class="glyphicon glyphicon-send"></i></button>
    </div>
</fieldset>