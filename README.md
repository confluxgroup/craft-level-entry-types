<img src="./src/icon.svg" width="100" alt="plugin icon logo" />

# Level Entry Types plugin for Craft CMS 3.x

![overview gif](./docs/screenshots/level-entry-types-overview.gif)

Allows developers to limit the entry types available at each level of a structure section.

## Requirements

This plugin requires Craft CMS 3.4.0 or later.

## Installation

You install Level Entry Types via the Plugin Store in the Craft Control Panel.

To install the plugin manually, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require confluxgroup/level-entry-types

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Level Entry Types.

## Level Entry Types Overview

This plugin allows you to specify available entry types to the user based on the current structure level of an entry.

For example, if you have a structure made up of landing pages and tabbed pages, you can ensure tabbed pages are only allowed as a descendant of a landing page.

This plugin will allow a user to select from a filtered list of entry types, or display an error message if a user has misconfigured a structure hierarchy.

## Configuring Level Entry Types

There are no control panel settings for this plugin.

Level Entry Types supports the standard multi-environment friendly config plugin settings file. Just copy the `level-entry-types.php` file from the `vendor/confluxgroup/craft-level-entry-types` directory to your Craft `config` directory and configure the settings to your liking.

### Example level-entry-types.php config file
```
<?php

return [
	'structures' => [
		// list your structure section handles		
		'sectionOneHandle' => [
			// add each level you want to limit
			1 => [
				// add each entry type that's allowed at this level
				'entryTypeHandle',
				'entryTypeTwoHandle
			],
			2 => [
				'entryTypeThreeHandle'
			]
		], // don't forget a comma if you're defining multiple structures
	]
];
```

1. In the structures array, add the section handle(s) (must be a structure) whose entry types you want to manage.
2. For each level of a structure, create an array of acceptable entry types. Each level can contain as many entry types as needed. *If a level is left blank, or not defined at all, the native Craft behavior of allowing any entry type at that level will apply.*
3. In the Craft control panel, for each structure using this plugin, add a new “Entry Type” column to your element index view. This is required to display errors to the end user from the element index page.

This plugin does not alter Craft’s native behavior when it comes to saving entries. If somehow an entry is on a level it shouldn’t be, this plugin will provide an error UI and a flash alert indicating such. Functionally, we aren’t preventing or changing native Craft behavior.

## Using Level Entry Types

Once your configuration file is in place and the columns have been added to the control panel element index pages you should be good to go.

### Consider the following example:

You have a *structure* of *Companies*. The top level entry should be the *Company* name. The second level should be various *Departments* in the company. The third level is various tabs on a *Department* page and can be any of *News*, *Team*, or *Custom Tab* types.

### With this plugin, the following will happen:
1. While creating new entries, the select dropdown options next to the “Entry Type” field will be enabled/disabled based on your configuration.
2. While creating new entries, selecting or changing a parent entry will automatically update the list of available entry types.
3. If the structure is adjusted to an invalid configuration, an error notification will display on the structure listing page and the edit entry page indicating to change the parent entry or entry type.


## Credits

Brought to you by [Conflux Group](https://confluxgroup.com) and [Cotter Interactive](https://cotterinteractive.com)
