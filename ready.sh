chmod 700 copy.sh fixperms.sh

./copy.sh 2>&1 >/dev/null

./fixperms.sh 2>&1 >/dev/null

echo "I'm good to go!"