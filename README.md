# Pastebin Extension for phpBB 3.3 by phpBB.de

## Function overview 📓

This extension adds a new page to phpBB where users can add and share code with others, e.g. to get help
from support, or distribute code snippets or ideas. 

Pastebin uses syntax highlighting from the _tempest/highlight_ package. For every entry the suiting language for the syntax highlighting can be selected 
when uploading or posting the file. Moderators can change the code and these settings. 

The administrator can change the following settings in the ACP:
- Which languages are available to the users
- What are the minimal and maximal storage duration of the snippets
- Which highlight-style is used
- If secret snippets can be created (secret snippets are only available to their author and everyone who knows the URL)


## Requirements 🧰

The Pastebin extension can only be used with the following requirements:
- PHP 8.4 or higher
- phpBB 3.3.17 or higher (might work with older versions, although not tested properly)

## Installation 🖥

Download the latest release from the GitHub releases page: https://github.com/phpbb-de/phpbb-ext-pastebin/releases 
and copy the contents of the zip file to your phpBB installation's extension directory (**Do not use the "Source Code" files!**):

    /ext

so that you get:

    /ext/phpbbde/pastebin

Go to "ACP" > "Customise" > "Extensions" and enable the "Pastebin" extension.

### Installation from GitHub ⚙️ 

Clone this repository with git to the extension directory with "phpbbde/pastebin" as vendor name:

    git clone https://github.com/phpbb-de/phpbb-ext-pastebin ext/phpbbde/pastebin

Install the Composer dependencies (adjust the path to composer.phar to match your directory structure):

    cd ext/phpbbde/pastebin
    ../../../composer.phar install --no-dev

Go to "ACP" > "Customise" > "Extensions" and enable the "Pastebin" extension.

## Added permissions 👮
On first installation, the extension adds group permissions to the "Standard Features" role and the "Standard Moderator" and "Full Moderator" roles. 
You will find these under the "Pastebin" tab within the user or moderator role settings.

**New user permissions:** 🧍‍♂️

- Can delete own Pastebin entries
- Can edit own Pastebin entries (Storage duration, Syntax highlighting)
- Can post Pastebin entries
- Can post non-pruned Pastebin entries
- Can post Pastebin entries without visual confirmation
- Can view Pastebin entries

**New moderator permissions:** 👨‍⚖️

- Can delete Pastebin entries
- Can edit Pastebin entries (Storage duration, syntax highlighting, code)
- Can deactivate pruning of selected Pastebin entries

**New admin permissions:** 🤴

- Can manage Pastebin settings

## Development and bug reports 🏗️ 

If you find a bug, please report it in the issue tracker: https://github.com/phpbb-de/phpbb-ext-pastebin/issues

## License 📜

[GPLv2](license.txt)
