<?php
/*
  Plugin Name: CMB2 Code Editor
  Plugin URI:
  Description:
  Version: 0.0.1
  Author: Marc Rabun
  Author URI:
  License: MIT
 */

/*
  The MIT License (MIT)

  Copyright (c) 2017 Marc Rabun

  Permission is hereby granted, free of charge, to any person obtaining a copy
  of this software and associated documentation files (the "Software"), to deal
  in the Software without restriction, including without limitation the rights
  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
  copies of the Software, and to permit persons to whom the Software is
  furnished to do so, subject to the following conditions:

  The above copyright notice and this permission notice shall be included in
  all copies or substantial portions of the Software.

  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
  THE SOFTWARE.
 */
/**
 * @package  CMB2CodeEditor
 * @version 0.0.1
 * 
 * * */
defined('ABSPATH') or die();
if (!class_exists('RBN001_CMB2CodeEditor', FALSE)) {

    class RBN001_CMB2CodeEditor {

        /**
         * version
         * @var string
         * */
        const VERSION = '0.0.1';

        /**
         * priority
         * @var int
         * */
        const PRIORITY = 9900;

        /**
         * 
         * URLs to codemiror libary
         * */
        /*
         * codemirror base URL
         * @var string
         */
        private $cm_base_url = null;
        /*
         * codemirror lib URL
         * @var string
         */
        private $cm_lib_url = null;
        /*
         * codemirror addon URL
         * @var string
         */
        private $cm_addon_url = null;
        /*
         * codemirror mode URL
         * @var string
         */
        private $cm_mode_url = null;
        /*
         * codemirror theme URL
         * @var string
         */
        private $cm_theme_url = null;

        function __construct() {
            $this->cm_base_url = plugins_url('vendor/codemirror' . DIRECTORY_SEPARATOR, __FILE__);
            $this->cm_lib_url = $cm_base_url . 'lib';
            $this->cm_mode_url = $cm_base_url . 'mode';
            $this->cm_theme_url = $cm_base_url . 'theme';
            $this->cm_addon_url = $cm_base_url . 'addon';
        }

        function cmb2_render_codeeditor_callback($field, $value, $object_id, $object_type, $field_type) {

            //options used for code mirror
            $cm_options = $field->args['options'];

            //enqueue scripts and styles used for codeeditor field
            $this->load_scripts($cm_options);

            $value = wp_parse_args($value, array(
                'cm_code' => ''
            ));
            
            $this->render($cm_options, $field_type, $value);
        }

        function cmb2_sanitize_codeeditor($override_value, $value) {
            return $value;
        }

        private function render($cm_options, $field_type, $value) {
            ?>

            <textarea class="codemirror_txtarea"
                      role="wp_codemirror"
                      data-mode="<?php echo $cm_options['mode']; ?>"
                      data-theme="<?php echo $cm_options['theme']; ?>"
                      name="<?php echo $field_type->_name('[cm_code]') ?>"
                      id="<?php echo $field_type->_id('_cm_code') ?>" 
                      value=""><?php echo $value['cm_code'] ?>
            </textarea>
            <?php
        }

        private function load_scripts($cm_options) {
            //TODO check for no codemirror libs, css or scripts

            wp_enqueue_style('codemirror-css', $cm_lib_url . '/codemirror.css', false);

            //this will load for each unique theme used in an istance of CodeMirror
            wp_enqueue_style('codemirror-' . $cm_options['theme'], $cm_theme_url . '/' . $cm_options['theme'] . '.css', false);

            wp_enqueue_style('codemirror-fullscreen-css', $cm_addon_url . '/display/fullscreen.css', false);
            wp_enqueue_style('custom-css', plugins_url('css/custom.css', __FILE__), false);

            wp_enqueue_script('codemirror-js', $cm_lib_url . '/codemirror.js', array(), false, false);
            wp_enqueue_script('codemirror-load', $cm_addon_url . '/mode/loadmode.js', array(), false, false);
            wp_enqueue_script('codemirror-fullscreen-js', $cm_addon_url . '/display/fullscreen.js', array(), false, false);
            wp_enqueue_script('codemirror-panel-js', $cm_addon_url . '/display/panel.js', array(), false, false);
            wp_enqueue_script('codemirror-setup', plugins_url('scripts/setup.js', __FILE__), array(), false, true);

            wp_add_inline_script('codemirror_base_url', "var cm_base_url = '" . $this->$cm_base_url . "';");
        }

    }

    new RBN001_CMB2CodeEditor();
}