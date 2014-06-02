# Most Popular Pages #
<p>
Contributors: Mohammed Khan<br />
Tags: Google Analytics
</p>

A WordPress plugin that uses Google Analytics to display your most popular pages from the last 30 days.

## Description ##
This is an *untested* first draft plugin (unfortunately) for WordPress which generates a page for your viewers containing
a list of your most popular pages from the last 30 days. Of course this plugin assumes you have a Google Analytics account
and have set it up on your WordPress site.

In your admin console you'll see a new option under Settings, clicking on that will take you to a page to input your Analytics
settings. It will also generate a link in your navigation bar called 'Most Popular Pages' which takes you to the page listing
your top 10 pages from the last 30 days ordered by number of views descending. **NB** You will end up with an error message if trying to
click the button in the navbar before having input your settings.

## Installation ##
1. Place all the files under `/wp-content/plugins/most-popular-pages/`.
2. Go to the Plugins section in admin and activate it.
3. Under settings click on 'Most Popular Pages'.
4. Input your Analytics settings, to find your settings visit [https://console.developers.google.com/](https://console.developers.google.com/) you can also take a look at [https://developers.google.com/analytics/solutions/articles/hello-analytics-api#register_project](https://developers.google.com/analytics/solutions/articles/hello-analytics-api#register_project) to understand what's going and maybe even do some troubleshooting.
5. That should hopefully do it. Click the link generated in the nav bar to see the results.
