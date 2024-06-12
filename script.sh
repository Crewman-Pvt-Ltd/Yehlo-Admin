#!/bin/bash
(crontab -l | grep -v "/usr/bin/php /Applications/MAMP/htdocs/Backend-Yehlo/artisan dm:disbursement") | crontab -

(crontab -l | grep -v "/usr/bin/php /Applications/MAMP/htdocs/Backend-Yehlo/artisan store:disbursement") | crontab -

