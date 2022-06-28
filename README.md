# DPG Event API Plugin

This plugin will make it possible to connect a website to the event API\
In the admin area there will be a settings page under the DPG-main section.
Here you can select the current event, clear cache and preload the event data.

## Custom views
View files can be overridden by theme templates. <br>
Create a folder in the theme Timver view folder called: 'event-api-frontend'<br>
In this folder you can copy the templates from the plugin view folder. <br>
Note: the system is depending on the file path. So you have to copy the sub folders.

### Implementation
There are a view optional short tags that can be used: <br>
- ```[dpg-ep-activities]```- display a list with all the activities <br>
- ```[dpg-ep-shops]```- display a list with all the shops <br>
- ```[dpg-ep-program-category]```- display a list with the program grouped by category <br>
- ```[dpg-ep-program]```- display a list with the program