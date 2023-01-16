<?php require_once 'config.php'; ?>
<?php if (!empty($_SERVER['HTTP_REFERER'])): ?>
<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
  </head>
  <body>
    <div class="container">
<?php endif; ?>


      <p>
        <?php _e('This research is carried out in the University of Luxembourg.'); ?>
        <?php _e('The purpose of the study is to understand users’ art preferences and their attentional correlates towards visual content.'); ?>
      </p>

      <h4><?php _e('Data collection'); ?></h4>
      <p>
        <?php _e('We collect the following data, as provided by each participant:'); ?>
        <ol class="list-indented">
          <li><?php _e('Art preferences, as numerical ratings.'); ?></li>
          <li><?php _e('Paintings recommendation quality, as numerical ratings.'); ?></li>
          <li><?php _e('Demographic information (gender and age), for statistical purposes.'); ?></li>
        </ol>
        <?php _e('We also collect IP addresses, to infer automatically the country of our participants.'); ?>
      </p>

      <h4><?php _e('Anonymity, secure storage, confidentiality'); ?></h4>
      <p>
        <?php _e('We follow the European privacy regulation (GDPR) for data collection and anonymization. Data will be kept confidential in a password-protected electronic format. Data will be stored for up to five years and will not be transferred outside EU/EEA area. We may report aggregated statistics about our participants in scientific publications.'); ?>
      </p>

      <h4><?php _e('Voluntary participation'); ?></h4>
      <p>
        <?php _e('Participation in the study is voluntary. You can discontinue participation at any time without obligation to disclose any specific reasons.'); ?>
      </p>

      <h4><?php _e('Rights of the study participant'); ?></h4>
      <p>
        <?php _e('It may be necessary to deviate from the rights of the data subject, as defined in GDPR and national legislation, if the exercising of the participant’s rights would likely prevent reaching the aim of the research study.'); ?>
        <!--
        <?php _e('The following rights may be deviated from:'); ?>
        <ol class="list-indented">
          <li><?php _e('The right to access data'); ?></li>
          <li><?php _e('The right to rectify information'); ?></li>
          <li><?php _e('The right to restrict processing'); ?></li>
          <li><?php _e('The right to be forgotten'); ?></li>
        </ol>
        -->
        <?php _e('The extent of your rights is related to the legal basis of processing of your personal data.'); ?>
        <?php _e('Exercising your rights requires proof of identity.'); ?>
      </p>

      <h4><?php _e('Funding information and contact'); ?></h4>
      <p>
        <?php _e('This research is supported by the Horizon 2020 FET program of the European Union (grant CHIST-ERA-20-BCI-001).'); ?>
      </p>
      <p>
        <b>Dr. Bereket A. Yilma</b>
        <br/>
        <b>Prof. Luis A. Leiva</b>
        <br/>
        firstname.lastname@uni.lu
      </p>

      <p>
        <i><?php _e('We thank you for your contribution to this research study.'); ?></i>
      </p>

<?php if (!empty($_SERVER['HTTP_REFERER'])): ?>
      <p>
        <a href="index.php">&laquo; <?php _e('Back'); ?></a>
      </p>
    </div><!-- .container -->
  </body>
</html>
<?php endif; ?>
