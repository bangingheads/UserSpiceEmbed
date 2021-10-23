# UserSpice Embed Plugin

This plugin helps generate dynamic meta data tags for SEO and embeds like Discord or Facebook.

UserSpice can be downloaded from their [website](https://userspice.com/) or on [GitHub](https://github.com/mudmin/UserSpice5)

## Setting Up

1. Copy the embed plugin folder from inside the repo into /usersc/plugins/
2. Open UserSpice Admin Panel and install plugin.
3. Configure plugin if you'd like to choose a default description.
4. Set descriptions for pages you'd like to have descriptions for.

## Plugin Configuration

In plugin configuration you can choose whether you'd like to have a default description for pages that do not have a description set.

## Setting Descriptions

In the page manager you will now have a field for a page description. This will be what is shown on the embed or Search Engine.

## Overriding

You can override the page title or description using the $embedTitle and $embedDescription variables before loading your template.

This allows you to dynamically set your meta title and/or description dynamically based on the title of the page.

## Troubleshooting

This plugin requires write permissions to the usersc/includes folder. Please make sure it can edit the usersc/includes/head_tags.php as well as create a backup of the file. You can manually update fields in this file to match the one in files/head_tags.php of the plugin if you would like instead.

## Questions

Any issues? Feel free to open an issue on Github or make a Pull Request.

Need help? Add me on Discord: BangingHeads#0001.

Any help with UserSpice can be asked in their [Discord](https://discord.gg/j25FeHu)
