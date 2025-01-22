I have dockerized this app for sake of convenience.

## Here are the magic commands to make it go
Beforehand you should install 2 apps in this order!
[WSL (Windows Subsystem for Linux)](https://apps.microsoft.com/detail/9PN20MSR04DW?hl=neutral&gl=LV&ocid=pdpshare)
[Docker 😍🐋](https://docs.docker.com/desktop/setup/install/windows-install/)

If you havent used any of this before, make sure that you are in the correct project directory inside the terminal (preferably VSCode or any other code editor you use)
```
docker-compose down
docker-compose build
docker-compose up -d
```
P.S. first one is if something goes wrong.
## Notes
### NOTE 1

`laravel.log` should be deleted because there will be an issue with permissions if it's not created by laravel and given the necessary permissions. Better yet, I added it to .gitignore
