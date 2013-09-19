<form class="tu-answers" action="" method="POST">
  <div class="tu-essay-answers">

    <div class="tu-form-row">
      <div class="tu-form-label">
        <?php echo apply_filters('tu_form_label', __('Your answer:', 'trainup'), 'your_answer'); ?>
      </div>
      <div class="tu-form-inputs">
        <div class="tu-form-input tu-form-text">
          <?php if ($wysiwyg) {
            wp_editor(
              $users_answer,
              'tuessayanswer',
              array(
                'editor_class'  => 'tu-wysiwyg',
                'textarea_name' => 'tu_answer',
                'media_buttons' => false
              )
            );
          } else { ?>
            <textarea name="tu_answer" rows="20" cols="90"><?php
              echo $users_answer;
            ?></textarea>
          <?php } ?>
        </div>
      </div>
    </div>

    <div class="tu-form-row">
      <div class="tu-form-label"></div>
      <div class="tu-form-inputs">
        <div class="tu-form-input tu-form-button">
          <button type="submit">
            <?php echo apply_filters('tu_form_button', __('Save my answer', 'trainup'), 'save_answer'); ?>
          </button>
        </div>
      </div>
    </div>
    
  </div>
</form>