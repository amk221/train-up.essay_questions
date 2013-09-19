<?php
if (!empty($notice)) {
  echo $notice;
}
?>

<p>
  <label>
    <input type="checkbox" name="tu_essay_wysiwyg" value="1"<?php
      echo $wysiwyg ? ' checked' : '';
    ?>>
    <?php
      printf(
      __('Enable %1$s editor', 'trainup'),
      '<acronym title="'.__('What You See Is What You Get', 'trainup').'">'.
        __('WYSIWYG', 'trainup').
      '</acronym>'
    );
    ?>
  </label>
</p>