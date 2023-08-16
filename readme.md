# FSE Theme Generator Plugin

A WordPress CLI plugin for generating Full Site Editing (FSE) themes with optional Sass support.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [Examples](#examples)
- [License](#license)

## Installation

To use the FSE Theme Generator Plugin, follow these steps:

1. Download the ZIP archive of this repository by clicking on the "Code" button and selecting "Download ZIP," or clone the repository to your local machine using Git:

   ```git clone https://github.com/HILAYTRIVEDI/fse-theme-generator.git```

2. Upload the extracted plugin folder to your WordPress installation's wp-content/plugins/ directory.

3. Activate the plugin through the WordPress admin dashboard:

4. Log in to your WordPress admin panel.
5. Go to "Plugins" in the left-hand menu.
6. Find the "FSE Theme Generator" plugin in the list.
7. Click the "Activate" button.

The plugin is now ready to use. You can access it using the WordPress CLI (wp) in the command line.

# Usage

Generate a new FSE theme using the following command:

```wp generate_fse_theme <theme-name> [--sass=<sass>]```

* `<theme-name>`: The name of the theme you want to generate.
* `--sass=<sass>`: Whether to support Sass. Use --sass=true to enable Sass support.

# Examples
Generate a new FSE theme named `"my_fse_theme"` with Sass support:

`wp generate_fse_theme my_fse_theme --sass=true`

License
This plugin is licensed under the GPL-2.0+ license. You are free to use, modify, and distribute it as per the terms of the GPL-2.0+ license.

# Author
Created by [Hilay Trivedi](https://profiles.wordpress.org/hilayt24/).

# Credits
The FSE Theme Generator Plugin is based on the WordPress CLI framework.