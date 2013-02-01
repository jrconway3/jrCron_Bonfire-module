# jrCron Bonfire Module

At first I planned to create an entire application in Bonfire, but I've found that for what I need I only have to create a single module.


This is it; the jrCron Bonfire Module. This module is an extension for Bonfire designed with the ability in mind of managing cron jobs and scrapers. It shows reports on your cron jobs, provides an interface to review and download exported files, and allows you to control what cron jobs will run and what time.


Initially I'm only configuring this to do what I need it to do for this job, but anyone may extend it if they please, or they can simply use the code I have for their own projects. I'll only extend it if I ever need to improve it for any reason.



### Q. Why not just call it "Cron"?

I originally called it that, but then I designed I should probably differentiate it from other potential modules that may be made in the future. So I gave it my own prefix.


### Q. Is this module plug-in and play?

No. Unfortunately, with this kind of thing its really pretty much impossible. It simply makes it easier to manage and watch cron jobs. You have to be the one to write your own cron jobs, but if you use the function wrappers properly, it will automatically keep records of your cron jobs and enable you to shut off certain jobs from running if you decide to go that route.

Naturally, you have to connect it up in cron. This is vital. It won't run at all if its not in cron in the first place. But my idea is that this module will work well as a wrapper for anything that you may want to run in cron. Primarily its for my own ease of use, but anyone can use it if they wish.



## Update #5:

Quick updates. Really distracted today, but I wanted to get something out. I posted an update that -should- fix my problems with closing existing cron sessions. But I still need to test it out.