=== CSV Download ===
Contributors: desie314
Tags: CSV, Comma Separated Values List, CSV Download, CSV Download Button, CSV Download Link, Download Button, Download Link
Tested up to: 4.5.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A plugin for WP developers to easily add CSV download links to the admin section or front end.

== Description ==
A plugin for WP developers to easily add CSV download links to the admin section or front end. When a specific GET variable is detected (from the download link) your data, which needs to be a multi-dimensional array, is passed to a function that converts it to a csv file and triggers the download.

Add the following code to **functions.php**.

= Admin Example =
`
/**
 * Instantiate CSVDownload class with appropriate arguments (listed in class).
 * Arguments are optional
 */
if (class_exists('CSVDownload')) {
  $csv_button = New CSVDownload(array(
    'post_types' => array('page'),
    'post_type_ids' => array(420, 114, 749),
    'metabox_title' => 'Download CSV Data',
    'help_text' => 'CSV file containing useful data.',
    'parameter' => 'csv_export_button',
    'button_text' => 'Download'
  ));
}

/**
 * Get results, convert to csv file, and trigger download.
 */
if(isset($_GET[$csv_button->parameter])) {
  add_action('admin_init', function(){
    // Get results array
    $results = get_csv_file_results();
    // Convert results array to csv file and trigger download.
    CSVDownload::download_csv_results(array(
      'results' => $results,
      'file_name' => 'csv_data'
    ));
    exit;
  }, 1);
}

/**
 * Get the results array for the csv button download.
 *
 * @return array
 */
function get_csv_file_results(){

  // Create multi-dimensional array.
  $results_array = array(
    array('Email','User Name','Favorite Color'), // Column headers
    array('fake@email.com','coolguy1','blue'),
    array('fake@email.com','coolguy2','orange'),
    array('fake@email.com','coolguy3','pink'),
    array('fake@email.com','coolguy4','red'),
  );

  // Return results array
  return $results_array;
}
`

= Front End Example =
Add a button element to your HTML.
`
<a href="<?php echo $_SERVER["REQUEST_URI"]; ?>?csv_export_button=1">Download</a>
`

Add init action callback and provide array data.
`
/**
 * Get results, convert to csv file, and trigger download.
 */
if(isset($_GET['csv_export_button'])) {
  add_action('init', function(){
    // Get results array
    $results = get_csv_file_results();
    // Convert results array to csv file and trigger download.
    CSVDownload::download_csv_results(array(
      'results' => $results,
      'file_name' => 'csv_data'
    ));
    exit;
  }, 1);
}

/**
 * Get the results array for the csv button download.
 *
 * @return array
 */
function get_csv_file_results(){

  // Create multi-dimensional array.
  $results_array = array(
    array('Email','User Name','Favorite Color'), // Column headers
    array('fake@email.com','coolguy1','blue'),
    array('fake@email.com','coolguy2','orange'),
    array('fake@email.com','coolguy3','pink'),
    array('fake@email.com','coolguy4','red'),
  );

  // Return results array
  return $results_array;
}
`

[AgencyLabs.com](http://agencylabs.com/) - A digital production studio.

== Installation ==
1. Upload the plugin files to the `/wp-content/plugins/csv-download` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress

== Screenshots ==
1. Admin section example metabox.
