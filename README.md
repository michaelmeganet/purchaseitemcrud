# purchaseitemcrud
Purchase items calculation of volume and weight in a CRUD page

NOTE : 

Please import this the purchaseitemcrud.sql first

this system will only add into the database if
1. Shaft
 the description contains "DIA"
and the Item_Code contains "Shaft"
2. Plate
 the description does not contain "DIA" 
 and the Item_Code does not contains "Shaft"

 Workflow :
 1. Data is inserted into Purchase table,
 2. When data has been inserted into purchase table, the code
 will detect it when the page has been redirected to index.php
 3. Data will be inputted into calPurchase table.