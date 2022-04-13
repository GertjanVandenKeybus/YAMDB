# YAMDB by Gertjan Van den Keybus
## Install Guide
1. Clone https://github.com/GertjanVandenKeybus/YAMDB.git
2. Run the following commands to install locally:
   1. composer install
   2. yarn install
   3. yarn encore prod
   4. Add a local DB string from your local environment to .env if needed.
   5. php bin/console doctrine:database:create
   6. php bin/console doctrine:migrations:migrate
3. The entire app is running on http://ursolsolutions.be
4. 

