if [ $(echo $USER) !=  "wzsulli" ]
then
    cp -rf /home/wzsulli/public_html/metube/media ./media
fi

if [ $(echo $USER) !=  "ssweetm" ]
then
    cp -rf /home/ssweetm/public_html/metube/media ./media
fi

if [ $(echo $USER) !=  "micah6" ]
then
    cp -rf /home/micah6/public_html/MeTube/media ./media
fi

chmod 755 media media/*
