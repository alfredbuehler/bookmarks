FORK NOTICE:

The original maintainer seems not to be interested to make this niffty tool work under owncloud 10.

Since there is a pull request in the main repo to make this work with owncloud 10, I forked and applied the patches.

Install and activate: 
- fork or download into an directory in apps directory of your owncloud installation
- goto Admin Settings > Administration > Apps
- click "show deactivated Apps" (wording might be different, my owncloud is in german language)
- activate bookmarks

You will be send to the update page, the update throws some Errors, but nothing serious.

Bookmarks work again! All my old bookmarks, I carry around since OC 5 are still there and lived happy after.

May the source be with you!

CAVEEAT:
The integrity check on the bookmarks app will fail, but it will work nonetheless. I do not know how to correct this issue, sorry.


Original README:
Bookmarks app
============

Maintainers:
------------
- [Brice Maron] aka eMerzh (https://github.com/eMerzh)

Developer setup info:
---------------------
### Installation:
Just clone this repo into one of your apps directory.


Status :
---------
Rewrite by [Stefan Klemm] aka ganomi (https://github.com/ganomi)

* This is a refactored / rewritten version of the bookmarks app using the app framework
* Dependency Injection for user and db is used througout the controllers
* The Routing features a consistent rest api
* The Routing provides some legacy routes, so that for exampe the Android Bookmarks App still works.
* Merged all the changes from https://github.com/owncloud/bookmarks/pull/68 and added visual fixes. App uses the App Framework Styling on the Client side now.

There is a publicly available api that provides access to bookmarks per user. (This is usefull in connection with the Wordpress Plugin https://github.com/mario-nolte/oc2wp-bookmarks)

Public Rest Api (JSON Formatting):
---------
Example Url:

../apps/bookmarks/public/rest/v1/bookmark?user=username&password=password&tags[]=firsttag&tags[]=anothertag&select[]=description&conjunction=AND&sortby=description

Parameters:

* user is a mandatory parameter. This will return all bookmarks from the specific user marked as public. (not yet possible!)
* by providing the users password all bookmarks will be returned
* tags[] can take multiple arguments and they are used to filter the requested bookmarks by tags
* conjunction (default = or) sets the tag filter to OR or to AND
* select[] takes multiple arguments. By default only url and title per bookmark are returned. Further you can select any attribute of the bookmarks table and also the attribute "tags"
* sortby takes and attribute that results will be sorted by descending.
