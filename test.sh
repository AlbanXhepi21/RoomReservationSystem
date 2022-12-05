git fetch &> /dev/null
diffs=$(git diff master origin/master)

if [ -z "$diffs" ]
then
  echo "Empty"
else
echo "not empty"
fi
