# concerto


Be careful -- this library is in its "alpha stage". DO NOT USE IN PRODUCTION.

## Notes

This library is an inheritance of several classes created from old PHP versions (I mean,
PHP 4 or even PHP 3) and involded in several projets. Concerto is a rewrite using
a namespace and very simple capabilities.

This idea behind Concerto is to offer some "out-of-the box" capabilities for some
weak points in PHP:

 - UTF-8 support (through std::XXX static functions).
 - Logging capabilities.
 - SQL capabilities based on a thin layer over PDO.
 - DAO (Data Access Object) upon the SQL class.
 - A simple to use O/R mapping. Simpler than Hibernate but having some
   capabilities included like optimistic updates, etc.

## A basic application

Based on what we know about applications, a BasicApplication offers a way to use
easily an out-of-box application.

    
