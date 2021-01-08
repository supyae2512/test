#!/bin/bash

printf "===> Get remote URL...\n"
REMOTE_URL=`git ls-remote --get-url`

PROJECT_NAME="$(cut -d '/' -f4 <<< $REMOTE_URL )"
REPO_NAME="$(cut -d '/' -f5 <<< $REMOTE_URL | sed 's/\.[^ ]*//g')"
BITBUCKET_URL="https://bitbucket.org"

# fetch tags, to be sure we have all the require info
printf "===> Fetch tags from repository...\n"
git fetch --tags

GIT_TAG=`git describe --tags`
RELEASE_NUMBER="$(cut -d '-' -f3 <<< $GIT_TAG )"
DATE=`date +%Y-%m-%d`
COMMIT_HASH="$(git log $(git describe --tags $(git rev-list --tags --max-count=1)) -1  --pretty=format:"%H")"
COMMIT_HASH_SHORT="$(git log $(git describe --tags $(git rev-list --tags --max-count=1)) -1  --pretty=format:"%h")"
COMMIT_MESSAGE="$(git log $(git describe --tags $(git rev-list --tags --max-count=1)) -1  --pretty=format:"%s")"
COMMIT_URL="$BITBUCKET_URL/$PROJECT_NAME/$REPO_NAME/commits"

printf "===> Writing changelog...\n"
previous_tag=0
for current_tag in $(git tag --sort=-creatordate)
do

if [ "$previous_tag" != 0 ];then
    tag_date=$(git log -1 --pretty=format:'%ad' --date=short ${previous_tag})
    release_number="$(cut -d '-' -f3 <<< ${previous_tag} )"
    # TODO: check latest release number as written in changelog.md file
    echo "## ${release_number} (${tag_date})\n\n" >> ./docs/CHANGELOG.md
    echo "$(git log ${current_tag}...${previous_tag} --pretty=format:'*  %s [View]('$COMMIT_URL'/%H)' --reverse | grep -v Merge)" >> ./docs/CHANGELOG.md
    echo "\n\n" >> ./docs/CHANGELOG.md
fi
previous_tag=${current_tag}
done

# Commit updated
printf "===> Push to master...\n"
git add ./docs/CHANGELOG.md
git commit -m "Add updated changelog file for release"
git push origin master
