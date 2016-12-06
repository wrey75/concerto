# concerto

This library is now mature.

It is currently used in production for the French website 
<http://www.koikonfait.com/> . I mean there is no official release yet and the documentation 
is mainly inside the source code (which is nice for autocompletion in Eclipse).

## Notes

This library is an inheritance of several classes created from old PHP versions (I mean,
PHP 4 or even PHP 3) and involved in several projets. Concerto is a rewrite using
a namespace and very simple capabilities.

This idea behind Concerto is to offer some "out-of-the box" capabilities for some
weak points in PHP:

 - UTF-8 support (through std::XXX static functions).
 - Logging capabilities (beta).
 - SQL capabilities based on a thin layer over PDO (beta).
 - DAO (Data Access Object) upon the SQL class (beta).
 - A simple to use O/R mapping. Simpler than Hibernate but having some
   capabilities included like optimistic updates, etc.

## A basic application

Based on what we know about applications, a BasicApplication offers a way to use
easily an out-of-box application. Check the documentation of the class to see how
you can extend this class.

## The `std` class
The basic class used everywhere. This class is basically an "helper" having all
the methods static. Once of my favorite is the "tag()" which creates a HTML tag
based on the tag name and their attributes passed as an array. The method is in
charge of cleaning the text in the values of the attributes.

If you use "get()" to retrieve values passed in the query string, you have a
default behaviour translating "<" into "< " to ruin efforts to try XSS on your site.
It is working to avoid script injection, but there is no SQL protection because 
you should use the mapping rather than creating SQL sentences manually.


## The `Logger` class

To log data. You can create as many loggers as you want.

## The `DataTable` class

Used to print tables (using the "\<table\>" tag). Note this class uses the DataTables
JQuery library which can be a little old-style but working pretty well for 90% of the
basic needs. There are some very enthousiastic mathods to avoid coding Javascript
for this library.

