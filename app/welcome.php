<div class="container">
<main>

  <h1><?php _e('Welcome'); ?></h1>

  <p>
    <?php _e('We want to evaluate the quality of painting recommendations produced by different systems.'); ?>
    <?php _e('The study takes about 5 minutes to complete.'); ?>
  </p>

  <p class="alert alert-info">
    <b><?php _e('Procedure:'); ?></b>
    <?php _e('First, you will express your art preferences by rating a few paintings.'); ?>
    <?php _e('Then, you will be presented with a set of recommendations based on your preferences.'); ?>
    <?php _e('You must rate those recommendations in a 5-point scale according to four criteria.'); ?>
  </p>

<!--
  <p class="alert alert-danger">
    <b><?php _e('Pay attention!'); ?></b>
    <?php _e('You will see some random control descriptions that should be rated the lowest (i.e. select the "strongly disagree" option).'); ?>
  </p>
-->

  <form class="pt-3" method="post" action="save-welcome.php">

    <p class="text-muted"><?php _e('First we need to know a little bit about you.'); ?></p>

    <div class="form-row">
      <div class="form-group col-md-3">
        <label for="gender" class="question"><?php _e('Gender'); ?></label>
        <select class="form-control" id="gender" name="gender" required>
          <option value=""></option>
          <option value="male" <?php if (DEBUG) echo ' selected' ?>><?php _e('Male'); ?></option>
          <option value="female"><?php _e('Female'); ?></option>
          <option value="other"><?php _e('Other'); ?></option>
          <option value="NA"><?php _e('Prefer not to say'); ?></option>
        </select>
      </div>

      <div class="form-group col-md-3">
        <label for="age" class="question"><?php _e('Age'); ?></label>
        <input id="age" name="age" type="number" class="form-control" min="18" max="99" required <?php if (DEBUG) echo 'value="18"'; ?> />
      </div>
    </div><!-- .row -->

    <div class="form-group">
      <label for="visit_profile" class="question"><?php _e('What is your museum visiting style?'); ?></label>
      <div class="form-check">
        <label class="form-check-label">
          <input class="form-check-input" type="radio" name="visit_profile" id="visit_profile" value="ant" required <?php if (DEBUG) echo ' checked'; ?> />
          <?php _e('I spend a long time observing all exhibits and move close to the walls and the exhibits avoiding empty space.'); ?>
        </label>
      </div>
      <div class="form-check">
        <label class="form-check-label">
          <input class="form-check-input" type="radio" name="visit_profile" value="fish" />
          <?php _e('I walk mostly through empty space making just a few stops and see most of the exhibits but for a short time.'); ?>
        </label>
      </div>
      <div class="form-check">
        <label class="form-check-label">
          <input class="form-check-input" type="radio" name="visit_profile" value="grasshopper" />
          <?php _e('I see only exhibits I am interested in. I walk through empty space and stay for a long time only in front of selected exhibits.'); ?>
        </label>
      </div>
      <div class="form-check">
        <label class="form-check-label">
          <input class="form-check-input" type="radio" name="visit_profile" value="butterfly" />
          <?php _e('I frequently change the direction of my tour, usually avoiding empty space. I see almost all exhibits, but time varies between exhibits.'); ?>
        </label>
      </div>
    </div>

    <div class="form-group">
      <label for="visit_popular" class="question"><?php _e('How likely are you interested in visiting popular paintings?'); ?></label>
      <div class="form-check">
        <label class="form-check-label">
          <input class="form-check-input" type="radio" name="visit_popular" id="visit_popular" value="1" required <?php if (DEBUG) echo ' checked'; ?> />
          1 &rarr; <?php _e('I don’t want to see popular paintings'); ?>
        </label>
      </div>
      <div class="form-check">
        <label class="form-check-label">
          <input class="form-check-input" type="radio" name="visit_popular" value="2" />
          2
        </label>
      </div>
      <div class="form-check">
        <label class="form-check-label">
          <input class="form-check-input" type="radio" name="visit_popular" value="3" />
          3
        </label>
      </div>
      <div class="form-check">
        <label class="form-check-label">
          <input class="form-check-input" type="radio" name="visit_popular" value="4" />
          4
        </label>
      </div>
      <div class="form-check">
        <label class="form-check-label">
          <input class="form-check-input" type="radio" name="visit_popular" value="5" />
          5 &rarr; <?php _e('I want to see as many popular paintings as possible'); ?>
        </label>
      </div>
    </div>

    <div class="form-group">
      <div class="form-check">
        <label class="form-check-label agree">
          <input class="form-check-input" type="checkbox" required <?php if (DEBUG) echo ' checked'; ?> />
          <?php echo sprintf(_('I have read and accept the <a href="%s">terms and conditions</a> of the study.'), 'terms.php'); ?>
        </label>
      </div>
    </div>

    <div class="foo">
      <button type="submit" class="btn btn-primary"><?php _e('Begin study'); ?></button>
      <?php if (DEBUG): ?>
      <input type="hidden" name="debug" value="1" />
      <?php endif; ?>
    </div>

  </form>

  <div class="modal fade" id="terms" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><?php _e('Terms and conditions'); ?></h5>
          <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <?php include 'terms.php'; ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php _e('Close'); ?></button>
        </div>
      </div>
    </div>
  </div>

  <script>
  $(function() {

      $('a').each(function() {
          $(this).attr('target', '_blank');
      });

      $('label.agree a').on('click', function(ev) {
          ev.preventDefault();
          $('#terms').modal('show');
      });

  });
  </script>

</main>
</div>
