# EduardoLedoServerSyncBundle
- Simple bundle for syncing your project with multiple servers (dev, staging, prod) via rsync.
```
$ composer require eduardoledo/server-sync-bundle

```

### Step 2: Enable the bundle in AppKernel.php

```php
<?php
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
# app/config/config.yml
eduardo_ledo_server_sync:
    servers:
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
---
