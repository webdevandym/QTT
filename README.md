#QTT Panel Project

╔═══╗╔════╗╔════╗  
║╔═╗║╚═╗╔═╝╚═╗╔═╝  
║║─║║──║║────║║  
║║╔╝║──║║────║║  
║╚╝─║──║║────║║  
╚═══╝──╚╝────╚╝  

Project moved -> https://github.com/webdevandym/tinyMCV

Last UPD: 11.07.15 (13:01)  
Pre Stable Version 2.2.6.3 build 37

Change Log:  

3.0.0 build 38
>New request System.

2.2.6.3 build 37
>All content in JSON files;  
>Auto check php code in content and processing, before put in json file and render. Compress whitespace.  
***

2.2.6.1 build 36
>Most stable work.  
>New request algorithm  
>Off Table checker in users query.   
***

2.2.5 build 35
>Load css and js asynchronously  up speed loading.  
>A lot of micro fix   
***

2.2.4 build 34
>Improved load speed  
>Improved operation speed  
>Encapsulate method and val store in InfoLoader\`s Classes.  
>Fix request to db in js lib . Improved speed and stable.  
>Micro fix in algorithm extra func.
***

2.2.2.2 build 33 [27.06.17]
>Add buttom to return today!  
>fix css  
>new Cache class  
>messStore now cached.  

***
2.2.2.1 build 32 [26.06.17]
>Update lang.json  
>Define \_\_ROOT\_\_ var  
>micro fix code...
>Refactoring messStore class.  
***

2.2.2 build 31 [26.06.17]
>Fix Editor bug and optimize alg.  
>Rebuild js lib in prototype style  
>High stable work.  
>>Add overtime value  
>
>A lot of stable and speed run updates.  
***

2.2.0 build 30 [22.06.17]
>BIG UPDTES  
>New Edit System.  
>Edit delays to improved speed   
>Hours now correct work with float value  
>Add checkBox, you can choose add object in desc or not.    
>Add day summ hours and week
***

2.1.4 build 29 [20.06.17]
>Update Calendar Style sheets  
>Rewrite Week days, input algorithm  
>messStore replace to Singleton pattern. for encapsulating data. GLOBAL var is bad idea;  
>Edit Connector class. Not strong change. After connect  unset all var with pass.  
>Encapsulating crypt class properties. To hide encrypting keys.

***

2.1.3.4 build 28 [19.06.17]
>micro edit style sheets  
>compress js code. (wrap moment func)
***

2.1.3.3 build 28 [19.06.17]
>Add previous and next month day for not full weekdays  
>Colored this for easy use  
>Calendar lib, rewrite func fill empty td. Add next or previous month days.
***

2.1.3 build 27 [19.06.17]
>encapsulate connector  
>protected connection setting   
***

2.1.2 build 26 [16.06.17]
>Cosmetic fix  
>Repair error warning  
>Fix addReport method
***

2.1.1 build 25 [16.06.17]
>New crypt Class. MD5 algorithm replace whit most strange openssl!  
>Micro fix  
***

2.1.0.1 build 24 [16.06.17]
>Add new class cached. To cache query;  
>Insert speed loading and updating page, data loading from RAM;
>>Life time 1 day  
>>Add serialize algorithm for saving to RAM!
>
>refactoring InfoLoader class whit using cache technology
***

2.0.9.8.4 build 23 [15.06.17]
>A lot of mini fix and Updates
***

2.0.9.7 build 21 [15.06.17]
>Fix report CRUD. Update Valid algorithm and insert report process  
>Fix datapicker style, in class .today  
***

2.0.9.6 build 20 [14.06.17]
>Avoid conflict when user switch extra function and one of they enable  
***

2.0.9.5 build 19 [14.06.17]
>Quick report fix a lot bug. Now work fine  
>Log Creator algorithm changed. More stable.
***

2.0.9.3 build 17 [13.06.17]
>Update validator js lib  
>>New rules to hide Object Name and Type  
>
>New extra function
>>Quick report, now you can add a lot equal report in 1 click.  
>>Fix little bug  
>
>Edit HTML logger
>>up speed  
***

2.0.9.1 build 14 [13.06.17]
>Refactoring JS lib  
>>Delete Description validator (can use empty value)  
>>Change insert and output algorithm in from   
>>Up response rate. Need more test.  
>
>Description change output format. mini fix. more usability.
***

2.0.8 build 13
>Fix system of report sort. poor compatibility with digital > 10;  
>future -> Refactoring js. Improved optimization and speed run.
***

2.0.8 build 12
>Replace getReportWeek() and getObjectName() methods query in SQL procedure  
>>load speed UP;
***

2.0.7a build 11
>replace addReport method in reportEditeTools class
>>improve query and remove user id query!  
>>micro edit reportEditor  
>
>Fix JS lib, Edit and Add function
***

2.0.6 build 10
>improved and optimize week-report query.  
>>creating view table  
>>reduced week-report main query duration, with use view table
>
>Log System: add new log algorithm
>>now you can put new record in top of list.
>
>ConnectDB and InfoLoader
>>add new method->existsTable(). check exists table, view etc.
***

2.0.5.1 build 9
>isolate "colorSyntax" class.  
>>used one syntax SQL  
>>simple syntax modification  
>>add new syntax as easy  
***

2.0.4 build 8
>refactoring log system  
>>add HTML logger  
>>>improve speed access  
>>>optimization slow method  
>>>HTML clear special symbol
>
>>SQL color syntax
***

2.0.4 build 5
>log sys extend class. now you can logged in html file.
>mini fix and rewrite method
***

2.0.3a
> add log system, experimental
>> create isolated class to Log System.
***

2.0.2:
> distributed js code  
> add link to change log
***

2.0.1:
> smart connector ;add configuration xml ;encrypt pass; throw exception ; nano-fix & updates
***
