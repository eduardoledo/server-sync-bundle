# EduardoLedoServerSyncBundle
- Simple bundle for syncing your project with multiple servers (dev, staging, prod) via rsync.

## Installation:

### Step 1: Download the bundle
Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle
```
$ composer require eduardoledo/server-sync-bundle
```

### Step 2: Enable the bundle in AppKernel.php

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new EduardoLedo\ServerSyncBundle(),
    );
}
```
### Step 3: Configure the bundle

```yaml
# app/config/parameters.yml
parameters:
    ...

eduardo_ledo_server_sync:
    servers:
        myserver:
            user: myuser                            # optional
            password: mypassword                    # optional
            host: host.example.com                  # required
            destination_dir: /home/myuser/dest_dir  # required
            exclude:                                # optional
                - dir1
                - dir2/*
                - dir3/*.ext
            exclude-from:                           # optional
                - file1
                - file2
                - fileN
```
## Usage:

You can list the configured servers from the console:
```
$ bin/console eduardoledo:server-sync:list-servers
```
This will output something like:
```

+------+------------+--------+--------+--------------------------+
| Name | Host       | User   | Pass   | Dest. dir                |
+------+------------+--------+--------+--------------------------+
| dev  | localhost  |        |        | /var/www/mysite.dev/     |
| prod | mysyte.com | myuser | mypass | /home/myuser/mysyte.com/ |
+------+------------+--------+--------+--------------------------+

```
Then you can:
- Perform a dry run:
```
$ bin/console eduardoledo:server-sync:upload --server=dev --dry-run
```

- Upload the files to one server:
```
$ bin/console eduardoledo:server-sync:upload --server=dev
```

- Upload the files to multiple servers:
```
$ bin/console eduardoledo:server-sync:upload --server=dev --server=prod
```

---
