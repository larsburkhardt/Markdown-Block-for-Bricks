<?php
/*
Plugin Name: Bricks Markdown Element
Description: Adds a custom Markdown element to Bricks builder
Version: 1.0
Author: Lars Burkhardt
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

require_once plugin_dir_path(__FILE__) . 'vendor/Parsedown.php';

function initialize_bricks_markdown_element() {
    if (!class_exists('\Bricks\Elements')) {
        return;
    }

    class Markdown_Element extends \Bricks\Element {
        public $category = 'custom';
        public $name = 'markdown';
        public $icon = 'ti-file-text';
        public $css_selector = '.markdown-content';
        
        public function get_label() {
            return esc_html__('Markdown', 'bricks');
        }
        
        public function set_controls() {
            $this->controls['markdown'] = [
                'tab' => 'content',
                'label' => esc_html__('Markdown Content', 'bricks'),
                'type' => 'textarea',
				'hasDynamicData' => 'text',
				
            ];
        }
        
		public function render() {
			$settings = $this->settings;
			$markdown = !empty($settings['markdown']) ? $settings['markdown'] : '';
			
			$this->set_attribute('_root', 'class', 'markdown-content');
			
			if (bricks_is_builder()) {
				// Im Builder: Zeige bearbeitbaren Text
				echo "<div {$this->render_attributes('_root')}>";
				echo "<div contenteditable='true' data-bricks-field='markdown'>";
				echo esc_html($markdown);
				echo "</div>";
				echo "</div>";
			} else {
				// Im Frontend: Render Markdown als HTML
				$parsedown = new Parsedown();
				$html = $parsedown->text($markdown);
				echo "<div {$this->render_attributes('_root')}>{$html}</div>";
			}
		}

    }

    \Bricks\Elements::register_element(__FILE__, 'markdown', 'Markdown_Element');
}

add_action('init', 'initialize_bricks_markdown_element', 11);
