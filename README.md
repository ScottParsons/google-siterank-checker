# Google Site Rank Checker

## Overview

A simple PHP class allowing you to check a website's page rank in the Google search results for given keywords.

## Usage

- Download or clone the package.
- ```cd``` into the project directory and run ```composer install```
- Within ```index.php```, modify the array of ```$data``` in the format of:

```
$data = [
   'en.wikipedia.org' => [
       'Gone with the Wind'
   ],
   'loremipsum.io' => [
       'lorum ipsum',
       'lorem ipsum dolor sit amet'
   ]
];
```

- Domains should be added without a protocol (http://, https://) and without the ```www``` subdomain
- Run ```php index.php```
- Example output:

```
en.wikipedia.org: Found in position 3 for phrase 'Gone with the Wind'
Search results link: https://en.wikipedia.org/wiki/Gone_with_the_Wind_(novel)

loremipsum.io: Found in position 2 for phrase 'lorum ipsum'
Search results link: https://loremipsum.io/

loremipsum.io: Found in position 3 for phrase 'lorem ipsum dolor sit amet'
Search results link: https://loremipsum.io/

```
- Modify the ```SEARCH_URL``` and ```PAGES_COUNT``` constants within ```PageChecker``` to suit your needs.
