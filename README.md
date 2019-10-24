# phpBB 3.2 Extension - phpBB.de Pastebin

## Installation

Clone into ext/phpbbde/pastebin:

    git clone https://github.com/phpbb-de/phpbb-ext-pastebin ext/phpbbde/pastebin

Go to ext/phpbbde/pastebin and install dependencies:

	php composer.phar install --no-dev

If you wish to contribute to development, you should also consider installing the development dependencies by leaving out --no-dev.
	
Go to "ACP" > "Customise" > "Extensions" and enable the "phpBB.de pastebin" extension.

## Development

If you find a bug, please report it on https://github.com/phpbb-de/phpbb-ext-pastebin

## Automated Testing

We use automated unit tests including functional tests to prevent regressions. Check out our travis build below:

master: [![Build Status](https://travis-ci.org/phpbb-de/phpbb-ext-pastebin.png?branch=master)](http://travis-ci.org/phpbb-de/phpbb-ext-pastebin)

## License

[GPLv2](license.txt)
