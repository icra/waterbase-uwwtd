#push
git add *
git commit -m "update"
git push

#pull
folder="/var/www/vhosts/icradev.cat/waterbase.icradev.cat/waterbase-uwwtd"
cmd="cd $folder; ls; git pull;"
ssh root@217.61.208.188 "$cmd"
