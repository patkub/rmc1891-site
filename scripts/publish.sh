#!/bin/sh

#
# Github publish script
# @author: Patrick Kubiak
# Usage: ./publish.sh
#

# total number of steps
readonly st=6

# colors
red=`tput setaf 1`
green=`tput setaf 2`
yellow=`tput setaf 3`
blue=`tput setaf 4`
magenta=`tput setaf 5`
cyan=`tput setaf 6`
reset=`tput sgr0`

# get version from package.json
VERSION=$(node -p "require('./package.json').version")

# check arguments
if [ $# -ne 0 ]; then
    echo "Usage: ./publish.sh"
    exit 1
fi

echo "${green}Running rmc1891-site Github Publish Script... ${reset}"
echo "${green}Publishing... ${reset}"
echo "${yellow}Version: $VERSION${reset}"

echo "${yellow}[1/$st]${green} git branch v$VERSION${reset}"
git branch v$VERSION

echo "${yellow}[2/$st]${green} git checkout v$VERSION${reset}"
git checkout v$VERSION

echo "${yellow}[3/$st]${green} git push -u origin v$VERSION${reset}"
git push -u origin v$VERSION

echo "${yellow}[4/$st]${green} git tag $VERSION${reset}"
git tag $VERSION

echo "${yellow}[5/$st]${green} git push -u origin refs/tags/$VERSION${reset}"
git push -u origin refs/tags/$VERSION

echo "${yellow}[6/$st]${green} git checkout master${reset}"
git checkout master

echo "${green}Done! ${reset}"

exit
