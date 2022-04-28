# DPG Event API Plugin

This plugin will make it possible to connect a website to the event API\
In the admin area there will be a settings page under the DPG-main section.
Here you can select the current event, clear cache and preload the event data.

## Custom views
View files can be overridden by theme templates. <br>
Create a folder in the theme root called: 'dpg-wp-event-api-views'<br>
In this folder you can copy the templates from the plugin view folder. <br>
Note: the system is depending on the file path. So you have to copy the sub folders.

### Implementation
There are a view optional short tags that can be used:
[dpg-ep-activities] ( display a list with all the activities )
[dpg-ep-shops] ( display a list with all the shops )
[dpg-ep-program] ( display a list with the program )

## Release notes

### 1.0.3
Added Twig templating
It's possible to create a custom template for a theme. 
Implemented 3 front end twig files.

### 1.0.2
Loading data in admin via AJAX to check the events.

### 1.0.1
Implemented Event API in admin
Complete Admin settings pages
Clear cache in admin

### 1.0.0
Initial setup