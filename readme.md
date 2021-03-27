
PHP based helper CLI tool for easier development of packages for SugarCRM

Usage
=====
0. Installation
wget https://github.com/litvinovandrew/sucode/blob/af13ac796a4a7ed1712ae391c75bd8ea0cf5e67c/build/sucode.phar && cp /usr/bin/sucode

1. init
Inits a directory and directory structure for new package development

2. add
Used to add some frequently used functionalite to the SugarCRM package
  [1 ] Init hooks for module
  [2 ] Create custom field
  [3 ] Create one-to-many relationship
  [4 ] Create many-to-many relationship
  [5 ] Add script to JSGroupings
  [6 ] Add admin section/layout
  [7 ] Create Api Endpoint
  [8 ] Add Scheduler
  [9 ] Add field to filter[n/a]
  [10] Add action menu[n/a]
  [11] Create Bean wizard[n/a]


Changing and further development
================================

1) clone
2) composer install
3) make any needed changes
3) download box 
   phive install humbug/box --force-accept-unsigned
4) Build ./tools/box build && sudo mv sucode.phar /usr/bin/sucode
5) execute in command line `sucode` 