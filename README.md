# phpBB 3.2/3.3 Extension - phpBB.de Pastebin

## Installation

Copy the content of this repository via git clone:

    git clone https://github.com/phpbb-de/phpbb-ext-pastebin ext/phpbbde/pastebin

or create the following directory structure in your phpBB-root directory:

	ext/phpbbde/pastebin

and copy the repository content to it.

Go to "ACP" > "Customise" > "Extensions" and enable the "Pastebin" extension.

## Added permissions
The extension adds on the first install group permissions to "user standard" role and "Standard Moderator" 
and "Full Moderator" roles. You will find them in the user or moderator roles tab "Pastebin".

**New user permissions:**

- Can delete own pastebin entries
- Can edit own pastebin entries (Storage duration, Syntax highlighting)
- Can post pastebin entries
- Can post non-pruned pastebin entries
- Can post pastebin entries without visual confirmation
- Can view pastebin entries

**New moderator permissions:**

- Can delete pastebin entries
- Can edit pastebin entries (Storage duration, Syntax highlighting)
- Can deactivate pruning of selected pastebin entries

## Development and bug reports

If you find a bug, please report it on https://github.com/phpbb-de/phpbb-ext-pastebin

## Automated Testing

We use automated unit tests including functional tests to prevent regressions. Check out our travis build below:

3.2.x: [![Build Status](https://travis-ci.org/phpbb-de/phpbb-ext-pastebin.png?branch=3.2.x)](http://travis-ci.org/phpbb-de/phpbb-ext-pastebin)

## License

[GPLv2](license.txt)
