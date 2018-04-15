## xandria


A library of key object-oriented programming patterns which, coupled with a [demo application](https://github.com/johnniewalker/xandria-demo), shows them being implemented and put to use. 

Patterns include those involved in Domain Driven Design, Patterns of Enterprise Architecture and Object-to-Relational Mapping.

## Quick Start

### Project Home


The project is open source and managed in github at: 

* https://github.com/johnniewalker/xandria


### Get Xandria



### How developers get and test the Xandra library

## Get the source

Use the command line to navigate to the directory on your work station where you develop libraries. Create one if necessary.

Clone the repository from Github.

Enter the project directory.

Ensure composer is installed.

Run the install --dev command:

~~~
# composer install --dev
~~~ 

Note the `--dev` option ensures that our testing libraries are also installed.

## Run the unit tests

Still within a command-line environment, ensure we are in the root directory of the project.

Run :

~~~~
# ./vendor/bin/codecept run unit
~~~~


## Using Xandria in your project

We assume you use [composer](https://getcomposer.org/) in your project. 

[Xandria is listed on Packagist](https://packagist.org/packages/johnniewalker/xandria).

Navigate to your project's root directory.


~~~~
# pwd
/My/Projects/SomeProject
~~~~

Then, '`composer require`' the library:

~~~~
# composer require johnniewalker/xandria
~~~~




### Why called Xandria?

The project is named Xandria after the Ancient Library of Alexandria, in Alexandria, Egypt. Which was one of the largest and most significant libraries of the ancient world. It functioned as a major center of scholarship from its construction in the 3rd century BC until the Roman conquest of Egypt in 30 BC. 

The first one is rumoured to have burned down destroying reams of ancient knowledge. Let's hope this digital one doesn't face the same dooooom.

### Git Intros


* https://help.github.com/articles/set-up-git -- Github Help -- Gets you up and running with Git/GitHub
* http://eagain.net/articles/git-for-computer-scientists/ -- Git for Computer Scientists - A nice overview of how git works. 

### Collaboration

# Use the Fork & Pull Model. 

Let's follow some advice from page 335 of the book, ZeroMQ by Pieter Hintjens (2013).

 Keep everything in the master and use GitHub pull requests to merge changes from multiple contributors. 

See: https://help.github.com/articles/using-pull-requests -- Fork & Pull Model





### Documentation Formatting

Documents and message are currently written in mark down format.

* https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet -- Cheatsheet
* https://help.github.com/articles/markdown-basics -- Markdown basics
* https://help.github.com/articles/github-flavored-markdown -- GitHub Flavored Markdown

