#!/bin/sh

sudo apt-get install git
sudo apt-get install nginx
sudo apt-get install php5-fpm
sudo apt-get install php5-cli

sudo service nginx start

sudo apt-get install texlive-fonts-extra
sudo apt-get install texlive-extra-utils
sudo apt-get install texlive-latex-extra
sudo apt-get install texlive
sudo apt-get install tex4ht

curl -s https://getcomposer.org/installer | php
php composer.phar install
