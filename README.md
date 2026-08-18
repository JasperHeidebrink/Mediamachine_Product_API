# Mediamachine Product Stock API Plugin

This plugin will make it possible to connect a website to the filemaker database of Mediamachine  
In the admin area there will be a settings page that allow a 'force reload'.
The plugin will check the current price and stock of the products based on the SKU number. <br>
The plugin will check the stock and price of the products using wp-cron, with an interval that can be configured in the admin settings (default: 5 minutes). <br>