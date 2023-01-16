<div class="modal fade" id="img-details" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php _e('Image details'); ?></h5>
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <img src="" id="img-preview" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php _e('Close'); ?></button>
      </div>
    </div>
  </div>
</div>

<script>
$("img").css('cursor', 'pointer').on("click", function() {
    $('#img-preview').attr('src', $(this).attr('src')).css('width', '100%');
    $('#img-details').modal('show');
});
</script>
