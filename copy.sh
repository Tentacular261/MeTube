if [ $(echo $USER) !=  "wzsulli" ]
then
    echo "copy wzsulli"
    cp -rf /home/wzsulli/public_html/metube/media .
    cp -rf /home/wzsulli/public_html/metube/user/profile_pictures user/
fi

if [ $(echo $USER) !=  "ssweetm" ]
then
    echo "copy ssweetm"
    cp -rf /home/ssweetm/public_html/metube/media .
    cp -rf /home/ssweetm/public_html/metube/user/profile_pictures user/
fi

if [ $(echo $USER) !=  "micah6" ]
then
    echo "copy micah6"
    cp -rf /home/micah6/public_html/MeTube/media .
    cp -rf /home/micah6/public_html/MeTube/user/profile_pictures user/
fi

chmod 755 media media/*
chmod 755 user/profile_pictures user/profile_pictures/*
