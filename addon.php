<?php
/*
Plugin Name: Train-up! Essay questions
Plugin URI: http://wptrainup.co.uk/
Description: Trainees are required to enter some text
Author: @amk221
Version: 0.0.2
License: GPL2
*/

namespace TU;

class Essay_questions_addon {

  /**
   * __construct
   *
   * Listen to the filters that the Train-Up! plugin provides, and latch on, 
   * inserting the new functionality where needed.
   * 
   * @access public
   */
  public function __construct() {
    $this->path = plugin_dir_path(__FILE__);
    $this->url  = plugin_dir_url(__FILE__);

    add_filter('tu_question_types', array($this, '_add_type'));
    add_filter('tu_question_meta_boxes', array($this, '_add_meta_box'), 10, 2);
    add_action('tu_meta_box_essay', array($this, '_meta_box'));
    add_action('tu_save_question_essay', array($this, '_save_question'));
    add_filter('tu_render_answers_essay', array($this, '_render_answers'), 10, 3);
    add_filter('tu_validate_answer_essay', array($this, '_validate_answer'), 10, 3);
  }

  /**
   * _add_type
   *
   * - Callback for when retrieving the hash of question types. 
   * - Insert our new 'essay' question type.
   * 
   * @param mixed $types
   *
   * @access public
   *
   * @return array The altered types
   */
  public function _add_type($types) {
    $types['essay'] = __('Essay', 'trainup');

    return $types;
  }

  /**
   * _add_meta_box
   *
   * - Callback for when the meta boxes are defined for Question admin screens
   * - Define one for our custom Question type: essay
   * 
   * @param mixed $meta_boxes
   *
   * @access public
   *
   * @return array The altered meta boxes
   */
  public function _add_meta_box($meta_boxes) {
    $meta_boxes['essay'] = array(
      'title'    => __('Essay options', 'trainup'),
      'context'  => 'advanced',
      'priority' => 'default'
    );

    return $meta_boxes;
  }

  /**
   * _meta_box
   *
   * - Callback function for an action that is fired when the 'essay' meta
   *   box is to be rendered.
   * - Echo out the view that lets the administrator configure this Question
   * 
   * @access public
   */
  public function _meta_box() {
    $notice = '';

    if (tu()->question->type === 'essay' && tu()->question->test->result_status !== 'draft') {
      $notice = tu()->message->view('notice', sprintf(
        __('<b>Important note:</b> Essay questions require you manually process the %1$s results', 'trainup'),
        strtolower(tu()->config['tests']['single'])
      ));
    }

    echo new View("{$this->path}/view/meta_box", array(
      'notice'  => $notice,
      'wysiwyg' => get_post_meta(tu()->question->ID, 'tu_essay_wysiwyg', true)
    ));
  }

  /**
   * _save_question
   *
   * - Fired when an essay question is saved
   * - Note: Essay style questions have no correct answer, therefore at this
   *   point we don't need to set the tu_answers post meta unlike most others.
   * - Save whether or not the WYSIWYG editor should be initialised on the 
   *   front end when viewing the Question.
   * 
   * @param mixed $question
   *
   * @access public
   */
  public function _save_question($question) {
    $wysiwyg = !empty($_POST['tu_essay_wysiwyg']);

    update_post_meta($question->ID, 'tu_essay_wysiwyg', $wysiwyg);
  }

  /**
   * _render_answers
   *
   * - Fired when the Essay-style question is shown
   * - Return a textarea to allow Trainees to enter their essay/whatever
   * 
   * @param mixed $content
   *
   * @access public
   *
   * @return string The altered content
   */
  public function _render_answers($content, $users_answer, $question) {
    return new View("{$this->path}/view/answers", array(
      'users_answer' => $users_answer,
      'question'     => $question,
      'wysiwyg'      => get_post_meta($question->ID, 'tu_essay_wysiwyg', true)
    ));
  }

  /**
   * _validate_answer
   *
   * - Fired when an essay question is validated
   * - Return null because this question type cannot be considered true or
   *   false. It has to be judged by a moderator and the percentage score
   *   manually set.
   * 
   * @param mixed $correct Whether or not the answer is correct
   * @param mixed $users_answer The user's attempted answer
   * @param mixed $question The question this answer is for
   *
   * @access public
   *
   * @return null
   */
  public function _validate_answer($correct, $users_answer, $question) {
    return null;
  }

}


add_action('plugins_loaded', function() { 
  new Essay_questions_addon;
});