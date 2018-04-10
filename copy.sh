if [ $(echo $USER) !=  "wzsulli" ]
then
    echo "copy wzsulli"
    cp -rf /home/wzsulli/public_html/metube/media .
fi

if [ $(echo $USER) !=  "ssweetm" ]
then
    echo "copy ssweetm"
    cp -rf /home/ssweetm/public_html/metube/media .
fi

if [ $(echo $USER) !=  "micah6" ]
then
    echo "copy micah6"
    cp -rf /home/micah6/public_html/MeTube/media .
fi

chmod 755 media media/*
