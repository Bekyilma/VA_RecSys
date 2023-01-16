<?php
// Load the first entry in the (randomized) conditions list.
$condition = $_SESSION['conditions'][0];
$images = load_recommendations($condition);
?>
<div class="container-fluid">
<main>

  <p class="mb-0 text-muted">
    <?php echo sprintf(_('Remaining ratings: %s'), count($_SESSION['conditions'])); ?>
  </p>

  <p class="mb-0">
    <b><?php _e('Look at we\'ve found for you:'); ?></b>
  </p>

  <div class="row">

    <div class="col-md p-3 left">

      <div class="container">
        <div class="row">
        <?php foreach ($images as $i => $image_id): ?>

            <div class="col-sm-6 col-md-6 col-lg-4 p-2">
              <img src="<?php echo imgpath($image_id, TRUE); ?>" class="img-thumbnail p-4" alt="<?php echo $image_id; ?>" />
            </div>

        <?php endforeach; ?>
        </div><!-- .row -->
      </div><!-- .gallery -->

    </div><!-- .left -->

    <div class="col-md p-3 right">

      <h4><?php _e('Please tell us what you think about these recommendations'); ?></h4>
      <hr />

      <form method="post" action="save-ratings.php">

        <?php function create_group($name, $description) { ?>
          <div class="group">
            <label class="question" for="<?php echo $name; ?>"><?php echo $description; ?></label>
            <div class="row no-gutters">
              <label class="col-lg">
                <input type="radio" name="<?php echo $name; ?>" value="1" />
                <?php _e('Strongly disagree'); ?>
              </label>
              <label class="col-lg">
                <input type="radio" name="<?php echo $name; ?>" value="2" />
                <?php _e('Disagree'); ?>
              </label>
              <label class="col-lg">
                <input type="radio" name="<?php echo $name; ?>" value="3" id="<?php echo $name; ?>" required <?php if (DEBUG) echo ' checked' ?> />
                <?php _e('Neutral'); ?>
              </label>
              <label class="col-lg">
                <input type="radio" name="<?php echo $name; ?>" value="4" />
                <?php _e('Agree'); ?>
              </label>
              <label class="col-lg">
                <input type="radio" name="<?php echo $name; ?>" value="5" />
                <?php _e('Strongly agree'); ?>
              </label>
            </div><!-- .row -->
          </div><!-- -group -->
        <?php } ?>

        <?php
        create_group('accuracy', _('These paintings match my personal preferences and interests'));
        create_group('diversity', _('These paintings are diverse'));
        create_group('novelty', _('I discovered paintings I didn’t know before'));
        create_group('serendipity', _('I found surprisingly interesting paintings'));
        ?>

        <input type="hidden" name="condition" value="<?php echo $condition; ?>" />
        <?php foreach ($images as $iid): ?>
          <input type="hidden" name="iids[]" value="<?php echo $iid; ?>" />
        <?php endforeach; ?>

        <input type="submit" value="Submit" class="btn btn-light" />
        <?php if (DEBUG): ?>
        <input type="hidden" name="debug" value="1" />
        <?php endif; ?>

      </form>

    </div><!-- .right -->

  </div><!-- .row -->

  <script>
  $(function() {
      function randomColor() {
          return '#' + Math.floor(Math.random() * 0xffffff).toString(16).padStart(6, '0');
      }

      let color = randomColor();

      $('.left').css({
          backgroundColor: color
      });
      $('.right').css({
          border: `3px solid ${color}`
      });
      $('.btn').css({
          border: `1px solid ${color}`
      });
  });
  </script>

  <?php include_once 'imgpreview.php'; ?>

</main>
</div><!-- .container -->
