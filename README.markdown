# jrCron Bonfire Module

At first I planned to create an entire application in Bonfire, but I've found that for what I need I only have to create a single module.


This is it; the jrCron Bonfire Module. This module is an extension for Bonfire designed with the ability in mind of managing cron jobs and scrapers. It shows reports on your cron jobs, provides an interface to review and download exported files, and allows you to control what cron jobs will run and what time.


Initially I'm only configuring this to do what I need it to do for this job, but anyone may extend it if they please, or they can simply use the code I have for their own projects. I'll only extend it if I ever need to improve it for any reason.



### Q. Why not just call it "Cron"?

I originally called it that, but then I designed I should probably differentiate it from other potential modules that may be made in the future. So I gave it my own prefix.


### Q. Is this module plug-in and play?

No. Unfortunately, with this kind of thing its really pretty much impossible. It simply makes it easier to manage and watch cron jobs. You have to be the one to write your own cron jobs, but if you use the function wrappers properly, it will automatically keep records of your cron jobs and enable you to shut off certain jobs from running if you decide to go that route.

Naturally, you have to connect it up in cron. This is vital. It won't run at all if its not in cron in the first place. But my idea is that this module will work well as a wrapper for anything that you may want to run in cron. Primarily its for my own ease of use, but anyone can use it if they wish.



## Update #6:

Its been about a week since I've updated, huh? Well, I've made some MAJOR updates over the past few days.

There's now officially a fully working Settings panel. The Settings > Cron panel lists all cron jobs available. These cron jobs must be defined in the new "$cron_jobs" variable array located in jrcron_model.php. The functions added to the model for managing cron job data will properly convert the arrays into objects, and will also grab them from the database if required.

The new migration, version 3, adds some functions to be used. I also fixed several bugs related to migrations. First of all, prefix was not being defined properly in migrations. I found several other odd issues as well which have been fixed. These right here resolved the error in migrations, array() to string conversion. I also fixed a bug where some settings in migration #2 were not being added to the administrator role.

In the new settings panel, it has a list of cron jobs. These settings can be updated on the main panel to be active or inactive. This means that when the job is run, it will not process the job, as defined in jrcron.php. There are also additional settings that can be updated by clicking on each individual job.

Memory Limit will change the memory limit on execution of the cron job. It automatically defines the 'M'; the field will only accept numeric characters. Time limit does the same but for time. I also added the ability to change what the file name will appear as in the control panel. The default file export name is defined in the $cron_jobs variable array. All of these settings are job-specific, and the ability to change the limit is useful if you don't want to have all jobs running at longer times, but you do want it to work on jobs that take a little longer to process.

I also completely overhauled the Exports page. The page now displays all jobs by default, but also displays information about the latest time the job was run. If it has been run, it'll show the latest export available for download on the right side. I also added a link at the bottom of the page called "Download Zip," which compiles all exported files listed on the page into one zip file.

In addition to this, there's now a separate page for each cron job. This will list every time the cron job has been run, and provided an exported file for each. Download Zip also appears on this page, and obviously, it will download the zip file of exported CSV's for every time that single cron job has run. This page does not have pagination at this time, though, so if these jobs are run at shorter intervals it may cause problems.

There is still no Reports page, though. I even removed the Reports page from the list so people wouldn't try to use it.