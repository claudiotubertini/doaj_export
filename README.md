doaj_export
===========
This plugin is actually made of three different plugins working together:  
* «Bibliographic Metabox» creates a metabox in the post editing page that allows the journal editor to insert metadata that will be used later in the exporting file  
* «Citation in meta tag» adds meta tag in the html page using the metadata already inserted by the preceding plugin. These meta tags are used by Google Scholar or Mendeley [Mendeley - Informations for Publishers] (http://www.mendeley.com/import/information-for-publishers/)  
* «DOAJ-XML-Export» creates an XML file based on DOAJ XML Schema with the metadata requested by DOAJ database  

IMPORTANT. This plugin is still in an early stage of development. 
One of the main feature that likely should be changed is the connection authors and their institutions. I avoided usign a new mysql table and worked only with string manipulation techniques. This is equivalent to asking editors to be aboslutely CAREFULL in inserting names and institution details into authors metabox. Example: John Ash, Oxford University; Cathy Black, MIT; PAY ATTENTION to the use of comma and semicolons. 