# Pastebin Extension for phpBB 3.3 from phpBB.de

## Function overview 📓

This extension adds a new page to phpBB where users can add and share code with others e.g. to get help
from support or distribute code snippets or ideas. 

Pastebin uses syntax highlighting from the _tempest/highlight_ package. For every entry the suiting language for the syntax highlighting can be selected 
during upload or posting of the file. Moderators can change the code. 

The admin can change these settings in the ACP:
- Which languages are available to the users
- What are the minimal and maximal storage duration of the snippets
- What highlight-style is used
- If secret snippets can be created (secret snippets are only available to the author and everyone who knows the URL)


## Requirements 🧰

The Pastebin extension can only be used with the following requirements:
- PHP 8.4 or higher
- phpBB 3.3.17 or higher (might work with older versions, although not tested properly)

## Installation 🖥

Download the latest release from the GitHub release page: https://github.com/phpbb-de/phpbb-ext-pastebin/releases 
and copy the content of the zip file to your phpBB installation's extension directory (**Do not use the "Source Code"-files!**):

    /ext

so that you get:

    /ext/phpbbde/pastebin

Go to "ACP" > "Customise" > "Extensions" and enable the "Pastebin" extension.

### Installation from GitHub ⚙️ 

Copy the content of this repository via git clone:

    git clone https://github.com/phpbb-de/phpbb-ext-pastebin ext/phpbbde/pastebin

Install the composer dependencies (path to composer.phar must be changed according to directory structure):

    cd ext/phpbbde/pastebin
    ../../../composer.phar install --no-dev

Go to "ACP" > "Customise" > "Extensions" and enable the "Pastebin" extension.

## Added permissions 👮
The extension adds on the first install group permissions to "user standard" role and "Standard Moderator" 
and "Full Moderator" roles. You will find them in the user or moderator roles tab "Pastebin".

**New user permissions:** 🧍‍♂️

- Can delete own Pastebin entries
- Can edit own Pastebin entries (Storage duration, Syntax highlighting)
- Can post Pastebin entries
- Can post non-pruned Pastebin entries
- Can post Pastebin entries without visual confirmation
- Can view Pastebin entries

**New moderator permissions:** 👨‍⚖️

- Can delete Pastebin entries
- Can edit Pastebin entries (Storage duration, Syntax highlighting)
- Can deactivate pruning of selected Pastebin entries

**New admin permissions:** 🤴

- Can manage Pastebin settings (admin permission)

## Development and bug reports 🏗️ 

If you find a bug, please report it on https://github.com/phpbb-de/phpbb-ext-pastebin/issues

## License 📜

[GPLv2](license.txt)
