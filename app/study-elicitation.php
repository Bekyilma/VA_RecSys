<?php function radio_group($name) { ?>
    <div class="group">
      <?php for ($i = 1; $i <= 5; $i++): ?>
        <div class="form-check form-check-inline">
          <label class="form-check-label">
            <input class="form-check-input mr-0" type="radio" name="prefs[<?php echo $name; ?>]" value="<?php echo $i; ?>"
              <?php if ($i == 3) echo 'id="'.$name.'" required'; ?>
              <?php if (DEBUG) echo 'checked'; ?>
            />
            <?php echo $i ;?>
          </label>
        </div>
      <?php endfor; ?>
    </div><!-- -group -->
<?php } ?>
<?php
$images = load_elicitations();
?>
<div class="container text-center">
<main>

  <div class="mb-3">
    <h4><?php _e('Please rate the following paintings based on your personal preferences'); ?></h4>
  </div>

  <form method="post" action="save-elicitation.php">

    <?php foreach ($images as $image_id): ?>
      <figure class="text-center">
        <img src="<?php echo imgpath($image_id); ?>" class="img-thumbnail" />
        <br />
        <?php radio_group($image_id); ?>
      </figure>
    <?php endforeach; ?>

    <input type="submit" value="Submit" class="btn btn-primary " />
    <?php if (DEBUG): ?>
    <input type="hidden" name="debug" value="1" />
    <?php endif; ?>

  </form>

  <?php include_once 'imgpreview.php'; ?>

</main>
</div><!-- .container -->
