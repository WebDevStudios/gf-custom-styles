Gravity Forms Custom Styles
===========================

* Super lightweight

* Allows you to add styles to gravity forms using wp-color-picker and other inputs.

* CSS is injected via `wp_head` in lieu of creating an additional css file. This is just to get rid of various server file writing/creation issues (although you can usually write to `wp_upload_dir` ), as well as the additional HTTP request generated, and lastly, various caching issues.

* If you'd like to see another style added, pull requests of course are welcome!

* Any feedback appreciated

### Contributors

* @ramiabraham Rami Abraham

* Jayvie Canono

### To-do:

* Hover and focus styles, via tabbed UI in admin settings
* Disable styles on mobile option
* Custom styles per form
* Syntax highlighting for custom css textarea

### How it works

* Install and activate
* Go to Forms --> Custom Styles
* Configure the custom style options as needed
* Save