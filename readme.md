<p>PHP based CLI tool for easier development of SugarCRM packages.</p> 
<p>Sugar + Code = sucode</p> 

Usage
-----
1. Installation

```properties
wget https://github.com/litvinovandrew/sucode/blob/af13ac796a4a7ed1712ae391c75bd8ea0cf5e67c/build/sucode.phar && 
cp /usr/bin/sucode
```

2. To start package development, execute
```properties
sucode init
```
this will create new directory and  all needed directory structure for new package development

3. To add to the package one of the common logic, execute 
```properties
sucode add
```
and then you will be able to:
- Init hooks for module
- Create custom field
- Create one-to-many relationship
- Create many-to-many relationship
- Add script to JSGroupings
- Add admin section/layout
- Create Api Endpoint
- Add Scheduler
- Add field to filter[n/a]
- Add action menu[n/a]
- Create Bean wizard[n/a]


4. View list of files that are different between development directory and SugarCRM directory
 ```properties
sucode diff
```

5. View difference between development directory and SugarCRM directory with possibitity to apply changes
 ```properties
sucode diff-full
```

6. Prepare installable zip archive 
 ```properties
sucode zip
```

Changing and further development
--------------------------------
If you want to adapt the logic for your needs :
1) clone
2) execute `composer install`
3) make any needed changes
3) download box 
   phive install humbug/box --force-accept-unsigned
4) Build ./tools/box build && sudo mv sucode.phar /usr/bin/sucode
5) execute from command line `sucode` 