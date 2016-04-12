# Child Page Tree #

[![Travis](https://img.shields.io/travis/obstschale/child-page-tree.svg?style=flat-square)](https://travis-ci.org/obstschale/child-page-tree)
[![GitHub license](https://img.shields.io/badge/license-GPLv3-blue.svg?style=flat-square)](https://raw.githubusercontent.com/obstschale/child-page-tree/master/LICENSE)
[![WordPress plugin](https://img.shields.io/wordpress/plugin/v/child-page-tree.svg?style=flat-square)](https://wordpress.org/plugins/child-page-tree/)
[![WordPress](https://img.shields.io/wordpress/plugin/dt/child-page-tree.svg?style=flat-square)](https://wordpress.org/plugins/child-page-tree/)
[![WordPress](https://img.shields.io/wordpress/v/child-page-tree.svg?style=flat-square)](https://wordpress.org/plugins/child-page-tree/)
[![Paypal](https://img.shields.io/badge/PayPal-donate-blue.svg?style=flat-square)](http://bit.ly/hhb-paypal)

**Contributors:** obstschale  
**Donate link:** [PayPal Donation](http://bit.ly/hhb-paypal)  
**Tags:** page, tree, child, children  
**Requires at least:** 4.0  
**Stable tag:** 1.0.1  
**Tested up to:** 4.5  

Display a page tree of all child pages of the current site

## Description ##

_Child Page Tree_ adds a select box to the page edit site right above the publish button. Using this select box you can decide whether to add and where to add the page tree. You can either add the page tree at the very top of page (prepend) or add it to the end of a page (append).

The output is a unorderd list with nested lists to reflect the hierarchy of the children. However this list can be changed using the build-in filter `child_page_tree_before_output`. This filter is applied before the list is added to content.

The plugin ships with basic stylesheet, which adds a icon to each link and does not show the hierarchy of the pages. The handler for this stylesheet ist `child_page_tree_style` and you can simple dequeue the script using `wp_dequeue_style()`. Another way to add your own custom style is to override the default style by using a custom CSS plugin. The page tree has an id (`ul#child_page_tree`) you can use and one class, either `.append` or `.prepend` depending its location.

### Requirements ###
* min. PHP 5.4

## Screenshots ##

### 1. Append page tree to site without any style ###
![Screenshot of the settings page](screenshots/screenshot-1.png)

### 2. Prepend page tree with custom stylesheet ###
![Screenshot of the settings page](screenshots/screenshot-2.png)

### 3. Example of usage for documentations ###
![Screenshot of the settings page](screenshots/screenshot-3.png)

### 4. Select box in backend ###
![Screenshot of the settings page](screenshots/screenshot-4.png)
![Screenshot of the settings page](screenshots/screenshot-5.png)


## Installation ##

You can download and install the plugin using the built in WordPress plugin installer. If you download it manually, make sure it is uploaded to \"/wp-content/plugins/child-page-tree/\". Activate Child Page Tree in the \"Plugins\" admin panel using the \"Activate\" link.


## Frequently Asked Questions ##

### Can I add the page tree to another location on the page? ###

No. Currently the plugin only supports appending and prepending. A shortcode could work but is not planned.

### Is it possible to add a page tree of page X into page Y? ###

No. Child Page Tree displays only the page tree of the current site.


## Changelog ##

### 1.0.1 ###
* [FIX] setter call which caused PHP errors
* [FIX] load textdomain for translations
* [ADD] Banner and Icon for WordPress.org Repo
* [ADD] Check PHP version before instantiating object

### 1.0.0 ###
* Initial release of the plugin
* [FEATURE] append or prepend page tree to site
* [HOOK::FILTER] `child_page_tree_before_output` child page tree before adding it to content
