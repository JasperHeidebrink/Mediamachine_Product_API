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
[dpg-ep-program-category] ( display a list with the program grouped by category )
[dpg-ep-program] ( display a list with the program )

## Release notes

### 1.1.3
Use the readme field for the ticket link

### 1.1.2
Check if the api view dir exists before implementing in Timber

### 1.1.1
Display a text when no result is found with the current filter.

### 1.1.0
Added program grouped by category
Added program ordered by activity

### 1.0.7
Always send an array to display_flash_message_html

### 1.0.6
Set correct return type to timber

### 1.0.5
Check requirements on init

### 1.0.4
Cleanup Admin triggers
A bit of optimizing and better checks
Code style

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