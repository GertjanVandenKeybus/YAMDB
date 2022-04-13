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

## General Information
1. The entire app is running on http://ursolsolutions.be (in dev mode still) if you don't want to run it locally
   1. Index page redirects to route /movies/popular where you can view the imported movies
   2. Imports can be done via the command
      1. php bin/console app:import-popular-movies {pages}
   3. or via the url /movies/import (import button in the navigation)
2. I added images, because it looks nice :)
3. You can view more pages with the arrow keys, depending on how many movies you imported. The maximum amount of movies you can import is 10000, since the API only allows the pagination to go to 500 (with 20 movies per page)
4. I added the ursolsolutions DB as a default DB connection in the .env file, I was using it as a dev environment, and the db_user will be deleted after this test has been verified, so go ahead and use it if you wish. Ursolsolutions uses the same DB, so once you import a number of movies on the site, your local environment with the remote DB will use the same movies.
