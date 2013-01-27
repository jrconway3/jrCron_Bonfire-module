# jrCron Bonfire Module

At first I planned to create an entire application in Bonfire, but I've found that for what I need I only have to create a single module.


This is it; the jrCron Bonfire Module. This module is an extension for Bonfire designed with the ability in mind of managing cron jobs and scrapers. It shows reports on your cron jobs, provides an interface to review and download exported files, and allows you to control what cron jobs will run and what time.


Initially I'm only configuring this to do what I need it to do for this job, but anyone may extend it if they please, or they can simply use the code I have for their own projects. I'll only extend it if I ever need to improve it for any reason.



### Q. Why not just call it "Cron"?

I originally called it that, but then I designed I should probably differentiate it from other potential modules that may be made in the future. So I gave it my own prefix.



## Update #1:

I've got a rough draft now. It should work successfully as a solo module, but its incomplete. I still have to connect some functions up. But in order to do that I have to actually test cron jobs in action. I don't think I can do that locally, though I can run some manual tests.

There's currently one issue I've noticed; when trying to install it throws an "array to string" conversion error in libraries/Log.php. As far as I can tell its nothing that I'm doing, its a bug in CI or maybe Bonfire. The migration process still works, you'll just get errors on a white screen and have to manually go back to the main page. I'm not sure how to fix this at this point.

Tomorrow I'll work on processing actual jobs and having them get archived in the logs. I'll need to create another table to archive cron logs, though. At this point I'm just going to go for the ability to literally turn off the cron job; I'm not going to enable the ability to customize cron jobs yet as its too much work for what I need to do. But that's a potential future goal if I can ever get around to doing it.