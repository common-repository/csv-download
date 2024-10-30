<?php
/*
Plugin Name: CSVDownload
Plugin URI:
Description: A plugin for WP developers to be able to add CSV download buttons to the admin section or front end.
Version: 1.0.0
Author: Desmond O'Grady
Author URI:
License: GPLv2
Copyright:
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class CSVDownload
{

  /**
   * Array of post types to display the button on.
   *
   * @var array
   *    Array of strings
   */
  private $post_types = array();

  /**
   * Array of post type ids to display the button on.
   *
   * @var array
   *    Array of integers
   */
  private $post_type_ids = array();

  /**
   * Title of the meta box.
   *
   * @var string
   */
  private $title = 'CSV Download';

  /**
   * Meta box help text.
   *
   * @var string
   */
  private $help_text = '';

  /**
   * Button text.
   *
   * @var string
   */
  private $button_text = 'Download';

  /**
   * HTML classes for the button element.
   *
   * @var string
   */
  private $button_classes = '';

  /**
   * Meta box context. Possible values: (normal, side, advanced).
   *
   * @var string
   */
  private $context = 'advanced';

  /**
   * The priority within the context where the boxes should show ('high', 'low').
   *
   * @var string
   */
  private $priority = 'default';

  /**
   * GET parameter that triggers csv download.
   *
   * @var string
   */
  public $parameter = 'csv_export_button';

  /**
   * CSVDownload constructor.
   *
   * @param $args
   */
  public function __construct($args = array()){

    // Optional Args
    if(!empty($args['post_types']))
      $this->post_types = (array) $args['post_types'];
    if(!empty($args['post_type_ids']))
      $this->post_type_ids = (array) $args['post_type_ids'];
    if(!empty($args['metabox_title']))
      $this->title = trim(strip_tags($args['metabox_title']));
    if(!empty($args['help_text']))
      $this->help_text = trim(strip_tags($args['help_text']));
    if(!empty($args['button_text']))
      $this->button_text = trim(strip_tags($args['button_text']));
    if(!empty($args['button_classes']))
      $this->button_classes = trim(strip_tags($args['button_classes']));
    if(!empty($args['parameter']))
      $this->parameter = trim(strip_tags($args['parameter']));
    if(!empty($args['context']) && in_array($args['context'], array('normal','side','advanced')))
      $this->parameter = $args['context'];
    if(!empty($args['priority']) && in_array($args['priority'], array('default','high','low')))
      $this->parameter = $args['priority'];

    $this->admin();
  }

  /**
   * Make sure it only adds action in admin section.
   */
  private function admin(){
    if(is_admin()){
      add_action( 'add_meta_boxes', array( $this, 'register_meta_box_csv_button' ) );
    }
  }

  /**
   * Register meta box for CSV button.
   */
  public function register_meta_box_csv_button(){
    global $post;

    // If post type ids is not empty and current post id is in that array...
    if(!empty($this->post_type_ids) && in_array($post->ID, $this->post_type_ids)){
      add_meta_box( 'csv_button_metabox', $this->title, array($this, 'csv_button_display_callback'), $this->post_types, $this->context, $this->priority );
    }
    // If post type ids is empty.
    elseif(empty($this->post_type_ids)){
      add_meta_box( 'csv_button_metabox', $this->title, array($this, 'csv_button_display_callback'), $this->post_types, $this->context, $this->priority );
    }
  }

  /**
   * Meta box display callback.
   *
   * @param WP_Post $post Current post object.
   */
  public function csv_button_display_callback($post){
    ?>
    <?php if(!empty($this->help_text)): ?>
      <p><?php echo $this->help_text; ?></p>
    <?php endif; ?>
    <a
      class="<?php echo $this->button_classes; ?>"
      href="<?php echo $_SERVER["REQUEST_URI"]; ?>&<?php echo $this->parameter; ?>=1">
      <?php echo $this->button_text; ?>
    </a>
    <?php
  }

  /**
   * Convert results array to csv file and trigger download.
   *
   * @param $args
   *    Array results & file name.
   */
  public static function download_csv_results($args){
    $results = !empty($args['results']) ? (array)$args['results'] : NULL;
    $name = !empty($args['file_name']) ? trim(strip_tags($args['file_name'])) : '';

    // Make sure there are results
    if(empty($results))
      return;

    // If name is empty then create a name.
    if(empty($name)){
      $name = md5(uniqid() . microtime(TRUE) . mt_rand()). '.csv';
    }

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename='.$name);
    header('Pragma: no-cache');
    header("Expires: 0");

    $outstream = fopen("php://output", "w");

    foreach($results as $result){
      fputcsv($outstream, $result);
    }

    fclose($outstream);
  }

}