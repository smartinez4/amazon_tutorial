UI_NAME="'$interfaceName'"
USERNAME="estimtrack"
APPNAME=${PWD##*/}
SERVER_WEBFACTION="web567.webfaction.com"
EXCLUDE_PATHS="--exclude=user_guide/ --exclude=.git --exclude=.gitignore --exclude=rsync --exclude=node_modules  --exclude=quirofans_test --exclude=.env"

###############
## NOT TOUCH ##
###############
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
UI_DIR="$DIR/"

while [ true ]
do
    rsync -Pavz --delete -e "ssh -p 22" $EXCLUDE_PATHS $UI_DIR $USERNAME@$SERVER_WEBFACTION:/home/$USERNAME/webapps/sergi/apps/$APPNAME
    sleep 3
done